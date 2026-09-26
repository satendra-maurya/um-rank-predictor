<?php

namespace App\Livewire;

use App\Enums\ActiveStatus;
use App\Enums\ExamCycleStatus;
use App\Models\Category;
use App\Models\Exam;
use App\Models\ExamAuthority;
use App\Models\ExamCycle;
use App\Models\ExamStage;
use App\Models\PredictionModel;
use App\Models\PredictionResult;
use App\Models\Shift;
use App\Models\State;
use App\Services\PredictionService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class RankPredictor extends Component
{
    // Step indicator: 1 = Main Category (SSC/Railway/State), 2 = State/Authority/Exam, 3 = Cycle, 4 = Stage, 5 = Prediction Form, 6 = Result
    public int $step = 1;

    // Selections
    public ?string $selectedCategorySlug = null; // 'ssc', 'railway', 'state-exams'

    public ?int $selectedStateId = null;

    public ?int $selectedAuthorityId = null;

    public ?int $selectedExamId = null;

    public ?int $selectedCycleId = null;

    public ?int $selectedStageId = null;

    // Form Fields
    public string $name = '';

    public string $roll_number = '';

    public ?string $dob = null;

    public ?float $raw_score = null;

    public ?int $category_id = null;

    public string $gender = 'Male';

    public ?int $shift_id = null;

    public bool $consent = false;

    // Submitting state flag & Result
    public bool $isAnalyzing = false;

    public ?PredictionResult $predictionResult = null;

    public function mount(?string $category = null, ?string $exam = null, ?string $year = null, ?string $stage = null): void
    {
        if ($category) {
            $this->selectCategory($category);
        }

        if ($exam) {
            $examModel = Exam::where('slug', $exam)->first();
            if ($examModel) {
                $this->selectExam($examModel->id);
            }
        }

        if ($year && $this->selectedExamId) {
            $cycle = ExamCycle::where('exam_id', $this->selectedExamId)->where('year', (int) $year)->first();
            if ($cycle) {
                $this->selectCycle($cycle->id);
            }
        }

        if ($stage && $this->selectedCycleId) {
            $stageModel = ExamStage::where('exam_cycle_id', $this->selectedCycleId)->where('slug', $stage)->first();
            if ($stageModel) {
                $this->selectStage($stageModel->id);
            }
        }
    }

    public function getAuthoritySlug(): ?string
    {
        if ($this->selectedExamId) {
            $exam = Exam::with('examAuthority')->find($this->selectedExamId);
            if ($exam?->examAuthority) {
                return $exam->examAuthority->slug;
            }
        }

        if ($this->selectedAuthorityId) {
            $auth = ExamAuthority::find($this->selectedAuthorityId);
            if ($auth) {
                return $auth->slug;
            }
        }

        if ($this->selectedCategorySlug) {
            $auth = ExamAuthority::where('slug', $this->selectedCategorySlug)
                ->orWhere('short_name', strtolower($this->selectedCategorySlug))
                ->first();
            if ($auth) {
                return $auth->slug;
            }
            if ($this->selectedCategorySlug === 'railway') {
                return 'rrb';
            }

            return $this->selectedCategorySlug;
        }

        return null;
    }

    public function getAuthorityName(): ?string
    {
        if ($this->selectedExamId) {
            $exam = Exam::with('examAuthority')->find($this->selectedExamId);
            if ($exam?->examAuthority) {
                return $exam->examAuthority->short_name ?: $exam->examAuthority->name;
            }
        }

        if ($this->selectedAuthorityId) {
            $auth = ExamAuthority::find($this->selectedAuthorityId);
            if ($auth) {
                return $auth->short_name ?: $auth->name;
            }
        }

        if ($this->selectedCategorySlug) {
            $auth = ExamAuthority::where('slug', $this->selectedCategorySlug)
                ->orWhere('short_name', strtolower($this->selectedCategorySlug))
                ->first();
            if ($auth) {
                return $auth->short_name ?: $auth->name;
            }
            if ($this->selectedCategorySlug === 'ssc') {
                return 'SSC';
            }
            if ($this->selectedCategorySlug === 'railway') {
                return 'RRB';
            }

            return strtoupper($this->selectedCategorySlug);
        }

        return null;
    }

    public function selectCategory(string $categorySlug): void
    {
        $this->selectedCategorySlug = strtolower($categorySlug);
        $auth = ExamAuthority::where('slug', $this->selectedCategorySlug)
            ->orWhere('short_name', strtolower($categorySlug))
            ->first();

        if ($auth) {
            $this->selectedAuthorityId = $auth->id;
            if ($auth->state_id) {
                $this->selectedStateId = $auth->state_id;
            }
        } else {
            $this->selectedStateId = null;
            $this->selectedAuthorityId = null;
        }

        $this->selectedExamId = null;
        $this->selectedCycleId = null;
        $this->selectedStageId = null;
        $this->step = 2;
    }

    public function selectState(int $stateId): void
    {
        $this->selectedStateId = $stateId;
        $this->selectedAuthorityId = null;
        $this->selectedExamId = null;
    }

    public function selectAuthority(int $authorityId): void
    {
        $this->selectedAuthorityId = $authorityId;
        $this->selectedExamId = null;
    }

    public function selectExam(int $examId): void
    {
        $this->selectedExamId = $examId;
        $exam = Exam::find($examId);
        if ($exam) {
            $this->selectedAuthorityId = $exam->exam_authority_id;
            if ($exam->examAuthority && $exam->examAuthority->state_id) {
                $this->selectedStateId = $exam->examAuthority->state_id;
            }
        }

        // Fetch active cycles
        $activeCycles = ExamCycle::where('exam_id', $examId)
            ->whereIn('status', [ExamCycleStatus::ACTIVE, ExamCycleStatus::DRAFT])
            ->orderBy('year', 'desc')
            ->get();

        if ($activeCycles->count() === 1) {
            $this->selectCycle($activeCycles->first()->id);
        } else {
            $this->step = 3;
        }
    }

    public function selectCycle(int $cycleId): void
    {
        $this->selectedCycleId = $cycleId;

        // Fetch active stages for this cycle
        $stages = ExamStage::where('exam_cycle_id', $cycleId)
            ->where('status', ActiveStatus::ACTIVE)
            ->orderBy('stage_order', 'asc')
            ->get();

        if ($stages->count() === 1) {
            // AUTOMATIC BYPASS if only 1 stage!
            $this->selectStage($stages->first()->id);
        } else {
            $this->step = 4;
        }
    }

    public function selectStage(int $stageId): void
    {
        $this->selectedStageId = $stageId;

        // Set default total questions if prediction model exists
        $model = PredictionModel::where('exam_stage_id', $stageId)
            ->where('status', ActiveStatus::ACTIVE)
            ->first();

        if ($model) {
            $config = $model->formula_config ?? [];
            if (isset($config['total_questions'])) {
                $this->total_questions = (int) $config['total_questions'];
            }
        }

        $this->step = 5;
    }

    public function backToStep(int $targetStep): void
    {
        if ($targetStep < $this->step) {
            $this->step = $targetStep;
        }
    }

    public function resetPredictor()
    {
        $authoritySlug = $this->getAuthoritySlug();

        $this->step = 1;
        $this->selectedCategorySlug = null;
        $this->selectedStateId = null;
        $this->selectedAuthorityId = null;
        $this->selectedExamId = null;
        $this->selectedCycleId = null;
        $this->selectedStageId = null;
        $this->name = '';
        $this->roll_number = '';
        $this->dob = null;
        $this->raw_score = null;
        $this->category_id = null;
        $this->gender = 'Male';
        $this->shift_id = null;
        $this->consent = false;
        $this->predictionResult = null;
        $this->isAnalyzing = false;
        $this->resetErrorBag();
        $this->resetValidation();

        if ($authoritySlug) {
            return redirect()->route('rank-predictor.authority.available-exams', ['authority' => $authoritySlug]);
        }

        return redirect()->route('home');
    }

    public function submitPrediction(PredictionService $service): void
    {
        $this->validate([
            'name' => 'required|string|min:2|max:191',
            'roll_number' => 'required|string|min:2|max:191',
            'dob' => 'required|date|before_or_equal:today',
            'raw_score' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'gender' => 'required|in:Male,Female,Other',
            'consent' => 'required|accepted',
        ], [
            'consent.accepted' => 'You must agree to the data processing consent to calculate your rank.',
        ]);

        $model = PredictionModel::where('exam_stage_id', $this->selectedStageId)
            ->where('status', ActiveStatus::ACTIVE)
            ->first();

        if (! $model) {
            // Fallback or auto-create prediction model for stage
            $model = PredictionModel::firstOrCreate(
                ['exam_stage_id' => $this->selectedStageId, 'version' => 'v1.0'],
                [
                    'name' => 'Default Prediction Engine',
                    'total_marks' => 200.0,
                    'negative_marking_ratio' => 0.25,
                    'status' => ActiveStatus::ACTIVE,
                ]
            );
        }

        $this->isAnalyzing = true;

        $result = $service->predict([
            'prediction_model_id' => $model->id,
            'exam_stage_id' => $this->selectedStageId,
            'shift_id' => $this->shift_id,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'candidate_name' => $this->name,
            'candidate_identifier' => $this->roll_number,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'raw_score' => $this->raw_score,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_token' => session()->getId(),
            'device_fingerprint' => hash('sha256', request()->ip().request()->userAgent()),
        ]);

        $this->predictionResult = $result;
        $this->isAnalyzing = false;
        $this->step = 6;
    }

    public function render()
    {
        // Dynamic options loading based on selections
        $states = State::where('status', ActiveStatus::ACTIVE)->orderBy('name')->get();

        $authorities = collect();
        $exams = collect();
        $cycles = collect();
        $stages = collect();
        $shifts = collect();

        if ($this->selectedCategorySlug === 'ssc') {
            $sscAuth = ExamAuthority::where('slug', 'ssc')->first();
            $authorities = $sscAuth ? collect([$sscAuth]) : ExamAuthority::where('level', 'CENTRAL')->get();
            $exams = Exam::whereIn('exam_authority_id', $authorities->pluck('id'))
                ->where('status', ActiveStatus::ACTIVE)
                ->orderBy('name')
                ->get();
        } elseif ($this->selectedCategorySlug === 'railway') {
            $railwayAuth = ExamAuthority::where('slug', 'rrb')->first();
            $authorities = $railwayAuth ? collect([$railwayAuth]) : ExamAuthority::where('level', 'CENTRAL')->get();
            $exams = Exam::whereIn('exam_authority_id', $authorities->pluck('id'))
                ->where('status', ActiveStatus::ACTIVE)
                ->orderBy('name')
                ->get();
        } elseif ($this->selectedCategorySlug === 'state-exams') {
            if ($this->selectedStateId) {
                $authorities = ExamAuthority::where('state_id', $this->selectedStateId)
                    ->where('status', ActiveStatus::ACTIVE)
                    ->orderBy('name')
                    ->get();
            }
            if ($this->selectedAuthorityId) {
                $exams = Exam::where('exam_authority_id', $this->selectedAuthorityId)
                    ->where('status', ActiveStatus::ACTIVE)
                    ->orderBy('name')
                    ->get();
            }
        }

        if ($this->selectedExamId) {
            $cycles = ExamCycle::where('exam_id', $this->selectedExamId)
                ->whereIn('status', [ExamCycleStatus::ACTIVE, ExamCycleStatus::DRAFT])
                ->orderBy('year', 'desc')
                ->get();
        }

        if ($this->selectedCycleId) {
            $stages = ExamStage::where('exam_cycle_id', $this->selectedCycleId)
                ->where('status', ActiveStatus::ACTIVE)
                ->orderBy('stage_order', 'asc')
                ->get();
        }

        if ($this->selectedStageId) {
            $shifts = Shift::where('exam_stage_id', $this->selectedStageId)
                ->where('status', ActiveStatus::ACTIVE)
                ->orderBy('shift_date')
                ->get();
        }

        $categories = Category::where('status', ActiveStatus::ACTIVE)->orderBy('sort_order')->get();

        $selectedExam = $this->selectedExamId ? Exam::find($this->selectedExamId) : null;
        $selectedCycle = $this->selectedCycleId ? ExamCycle::find($this->selectedCycleId) : null;
        $selectedStage = $this->selectedStageId ? ExamStage::find($this->selectedStageId) : null;

        return view('livewire.rank-predictor', [
            'states' => $states,
            'authorities' => $authorities,
            'exams' => $exams,
            'cycles' => $cycles,
            'stages' => $stages,
            'shifts' => $shifts,
            'categories' => $categories,
            'selectedExam' => $selectedExam,
            'selectedCycle' => $selectedCycle,
            'selectedStage' => $selectedStage,
            'authoritySlug' => $this->getAuthoritySlug(),
            'authorityName' => $this->getAuthorityName(),
        ]);
    }
}
