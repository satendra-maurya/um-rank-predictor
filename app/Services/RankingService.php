<?php

namespace App\Services;

use App\Enums\SubmissionTrustStatus;
use App\Models\CandidateSubmission;
use App\Models\ExamStage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RankingService
{
    /**
     * Allowed fields for score and tie-breaking.
     */
    protected const ALLOWED_FIELDS = [
        'id',
        'raw_score',
        'normalized_score',
        'dob',
        'candidate_name',
        'gender',
    ];

    /**
     * Default ranking configuration when none is set on the exam stage.
     */
    protected const DEFAULT_CONFIG = [
        'enabled' => true,
        'score_field' => 'raw_score',
        'rank_method' => 'ORDERED',
        'tie_breakers' => [
            ['field' => 'dob', 'direction' => 'asc', 'rule' => 'OLDER_HIGHER'],
            ['field' => 'candidate_name', 'direction' => 'asc'],
            ['field' => 'id', 'direction' => 'asc'],
        ],
    ];

    /**
     * Calculate all ranks and percentile for a candidate submission.
     *
     * @return array{
     *     overall_rank: ?int,
     *     category_rank: ?int,
     *     gender_rank: ?int,
     *     percentile: ?float,
     *     total_candidates: int
     * }
     */
    public function calculateForSubmission(CandidateSubmission $submission): array
    {
        $stage = $submission->relationLoaded('examStage')
            ? $submission->examStage
            : ExamStage::find($submission->exam_stage_id);

        $config = $this->getRankingConfig($stage);

        if (! ($config['enabled'] ?? true)) {
            return [
                'overall_rank' => null,
                'category_rank' => null,
                'gender_rank' => null,
                'percentile' => null,
                'total_candidates' => 0,
            ];
        }

        $orderingChain = $this->buildOrderingChain($config);

        // Scope dataset: Same exam stage + trusted candidates count
        $trustedCount = CandidateSubmission::where('exam_stage_id', $submission->exam_stage_id)
            ->where('trust_status', SubmissionTrustStatus::TRUSTED)
            ->count();

        $overallRank = $this->calculateOverallRank($submission, $orderingChain);
        $categoryRank = $this->calculateCategoryRank($submission, $orderingChain);
        $genderRank = $this->calculateGenderRank($submission, $orderingChain);
        $percentile = $this->calculatePercentile($overallRank, $trustedCount);

        return [
            'overall_rank' => $overallRank,
            'category_rank' => $categoryRank,
            'gender_rank' => $genderRank,
            'percentile' => $percentile,
            'total_candidates' => $trustedCount,
        ];
    }

    /**
     * Get ranking configuration with defaults applied.
     */
    public function getRankingConfig(?ExamStage $stage): array
    {
        if (! $stage || empty($stage->ranking_config) || ! is_array($stage->ranking_config)) {
            return self::DEFAULT_CONFIG;
        }

        $config = $stage->ranking_config;

        return [
            'enabled' => (bool) ($config['enabled'] ?? true),
            'score_field' => $this->validateScoreField($config['score_field'] ?? 'raw_score'),
            'rank_method' => $config['rank_method'] ?? 'ORDERED',
            'tie_breakers' => is_array($config['tie_breakers'] ?? null) ? $config['tie_breakers'] : self::DEFAULT_CONFIG['tie_breakers'],
        ];
    }

    /**
     * Calculate overall rank for submission.
     */
    protected function calculateOverallRank(CandidateSubmission $submission, array $orderingChain): ?int
    {
        return $this->calculateRank($submission, $orderingChain);
    }

    /**
     * Calculate category rank for submission.
     */
    protected function calculateCategoryRank(CandidateSubmission $submission, array $orderingChain): ?int
    {
        if (is_null($submission->category_id)) {
            return null;
        }

        return $this->calculateRank($submission, $orderingChain, ['category_id' => $submission->category_id]);
    }

    /**
     * Calculate gender rank for submission.
     */
    protected function calculateGenderRank(CandidateSubmission $submission, array $orderingChain): ?int
    {
        if (empty($submission->gender)) {
            return null;
        }

        return $this->calculateRank($submission, $orderingChain, ['gender' => $submission->gender]);
    }

    /**
     * Calculate percentile based on overall rank and total trusted candidates count.
     */
    protected function calculatePercentile(?int $overallRank, int $totalCandidates): ?float
    {
        if (is_null($overallRank) || $totalCandidates < 1) {
            return null;
        }

        $rawPercentile = (($totalCandidates - $overallRank + 1) / $totalCandidates) * 100;
        $clampedPercentile = min(99.99, max(0.01, round($rawPercentile, 2)));

        return $clampedPercentile;
    }

    /**
     * Core window function query for rank calculation.
     */
    protected function calculateRank(CandidateSubmission $submission, array $orderingChain, ?array $extraWhere = null): ?int
    {
        $orderBySql = $this->buildOrderBySql($orderingChain);

        $trustValue = $submission->trust_status instanceof SubmissionTrustStatus
            ? $submission->trust_status->value
            : (string) ($submission->trust_status ?? 'TRUSTED');

        $query = DB::table('candidate_submissions')
            ->where('exam_stage_id', $submission->exam_stage_id)
            ->where(function ($q) use ($submission) {
                $q->where('trust_status', SubmissionTrustStatus::TRUSTED->value)
                    ->orWhere('id', $submission->id);
            });

        if ($extraWhere) {
            foreach ($extraWhere as $col => $val) {
                $query->where($col, $val);
            }
        }

        $innerQuery = $query->select('id')
            ->selectRaw("ROW_NUMBER() OVER (ORDER BY {$orderBySql}) as rn");

        $result = DB::query()
            ->fromSub($innerQuery, 'r')
            ->where('id', $submission->id)
            ->value('rn');

        return $result ? (int) $result : null;
    }

    /**
     * Build the ordered list of fields and directions for SQL ORDER BY.
     */
    protected function buildOrderingChain(array $config): array
    {
        $chain = [];

        // 1. Primary Score Field
        $scoreField = $this->getScoreField($config);
        $chain[] = [
            'field' => $scoreField,
            'direction' => 'desc',
        ];

        // 2. Tie Breakers
        $tieBreakers = $config['tie_breakers'] ?? [];
        if (is_array($tieBreakers)) {
            foreach ($tieBreakers as $tb) {
                if (! is_array($tb) || empty($tb['field'])) {
                    continue;
                }

                $field = strtolower(trim($tb['field']));
                if (! in_array($field, self::ALLOWED_FIELDS, true)) {
                    Log::warning("RankingService: Invalid tie-breaker field ignored: {$field}");

                    continue;
                }

                if ($field === $scoreField) {
                    continue;
                }

                $direction = strtolower(trim($tb['direction'] ?? 'asc'));
                if (! in_array($direction, ['asc', 'desc'], true)) {
                    $direction = 'asc';
                }

                if ($field === 'dob' && ! empty($tb['rule'])) {
                    $rule = strtoupper(trim($tb['rule']));
                    if ($rule === 'YOUNGER_HIGHER') {
                        $direction = 'desc';
                    } elseif ($rule === 'OLDER_HIGHER') {
                        $direction = 'asc';
                    }
                }

                $alreadyInChain = false;
                foreach ($chain as $existing) {
                    if ($existing['field'] === $field) {
                        $alreadyInChain = true;
                        break;
                    }
                }

                if (! $alreadyInChain) {
                    $chain[] = [
                        'field' => $field,
                        'direction' => $direction,
                    ];
                }
            }
        }

        // 3. Fallback deterministic tie-breaker on id ASC
        $hasId = false;
        foreach ($chain as $existing) {
            if ($existing['field'] === 'id') {
                $hasId = true;
                break;
            }
        }

        if (! $hasId) {
            $chain[] = [
                'field' => 'id',
                'direction' => 'asc',
            ];
        }

        return $chain;
    }

    /**
     * Build SQL ORDER BY clause handling NULL values safely.
     */
    protected function buildOrderBySql(array $chain): string
    {
        $parts = [];
        foreach ($chain as $item) {
            $field = $item['field'];
            $dir = strtoupper($item['direction']) === 'DESC' ? 'DESC' : 'ASC';

            if ($field === 'id') {
                $parts[] = "id {$dir}";
            } else {
                $parts[] = "({$field} IS NULL) ASC, {$field} {$dir}";
            }
        }

        return implode(', ', $parts);
    }

    /**
     * Helper to get score_field.
     */
    protected function getScoreField(array $config): string
    {
        return $this->validateScoreField($config['score_field'] ?? 'raw_score');
    }

    /**
     * Validate score field against allowed whitelist.
     */
    protected function validateScoreField(string $field): string
    {
        $field = strtolower(trim($field));
        if (in_array($field, ['raw_score', 'normalized_score'], true)) {
            return $field;
        }

        return 'raw_score';
    }
}
