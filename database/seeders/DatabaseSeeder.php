<?php

namespace Database\Seeders;

use App\Enums\ExamAuthorityLevel;
use App\Enums\ExamCycleStatus;
use App\Enums\SubmissionTrustStatus;
use App\Models\CandidateSubmission;
use App\Models\Category;
use App\Models\Exam;
use App\Models\ExamAuthority;
use App\Models\ExamCycle;
use App\Models\ExamStage;
use App\Models\ImportantLink;
use App\Models\Notice;
use App\Models\PredictionModel;
use App\Models\PredictionResult;
use App\Models\Shift;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories
        $categoriesData = [
            ['name' => 'Unreserved / General', 'code' => 'UR', 'sort_order' => 1],
            ['name' => 'Other Backward Classes', 'code' => 'OBC', 'sort_order' => 2],
            ['name' => 'Economically Weaker Section', 'code' => 'EWS', 'sort_order' => 3],
            ['name' => 'Scheduled Caste', 'code' => 'SC', 'sort_order' => 4],
            ['name' => 'Scheduled Tribe', 'code' => 'ST', 'sort_order' => 5],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['code']] = Category::firstOrCreate(
                ['code' => $cat['code']],
                ['name' => $cat['name'], 'sort_order' => $cat['sort_order'], 'status' => 'ACTIVE']
            );
        }

        // 2. States
        $up = State::firstOrCreate(
            ['code' => 'UP'],
            ['name' => 'Uttar Pradesh', 'short_name' => 'UP', 'status' => 'ACTIVE']
        );

        $bihar = State::firstOrCreate(
            ['code' => 'BR'],
            ['name' => 'Bihar', 'short_name' => 'Bihar', 'status' => 'ACTIVE']
        );

        $rajasthan = State::firstOrCreate(
            ['code' => 'RJ'],
            ['name' => 'Rajasthan', 'short_name' => 'Rajasthan', 'status' => 'ACTIVE']
        );

        $mp = State::firstOrCreate(
            ['code' => 'MP'],
            ['name' => 'Madhya Pradesh', 'short_name' => 'MP', 'status' => 'ACTIVE']
        );

        // 3. Central Exam Authorities
        $ssc = ExamAuthority::firstOrCreate(
            ['slug' => 'ssc'],
            [
                'name' => 'Staff Selection Commission',
                'short_name' => 'SSC',
                'level' => ExamAuthorityLevel::CENTRAL,
                'state_id' => null,
                'website_url' => 'https://ssc.gov.in',
                'description' => 'Recruitment authority for Group B and C posts in Central Ministries and Departments.',
                'status' => 'ACTIVE',
            ]
        );

        $railway = ExamAuthority::firstOrCreate(
            ['slug' => 'rrb'],
            [
                'name' => 'Railway Recruitment Boards',
                'short_name' => 'Railway',
                'level' => ExamAuthorityLevel::CENTRAL,
                'state_id' => null,
                'website_url' => 'https://indianrailways.gov.in',
                'description' => 'Recruitment board for technical and non-technical staff in Indian Railways.',
                'status' => 'ACTIVE',
            ]
        );

        // State Exam Authorities
        $upsssc = ExamAuthority::firstOrCreate(
            ['slug' => 'upsssc'],
            [
                'name' => 'Uttar Pradesh Subordinate Services Selection Commission',
                'short_name' => 'UPSSSC',
                'level' => ExamAuthorityLevel::STATE,
                'state_id' => $up->id,
                'website_url' => 'https://upsssc.gov.in',
                'description' => 'State organization authorized to conduct examinations for appointments to Group C posts in UP.',
                'status' => 'ACTIVE',
            ]
        );

        $bpsc = ExamAuthority::firstOrCreate(
            ['slug' => 'bpsc'],
            [
                'name' => 'Bihar Public Service Commission',
                'short_name' => 'BPSC',
                'level' => ExamAuthorityLevel::STATE,
                'state_id' => $bihar->id,
                'website_url' => 'https://bpsc.bih.nic.in',
                'description' => 'Constitutional body created to select applicants for civil service jobs in Bihar.',
                'status' => 'ACTIVE',
            ]
        );

        // 4. Exams
        // SSC Exams
        $sscCgl = Exam::firstOrCreate(
            ['slug' => 'ssc-cgl'],
            [
                'exam_authority_id' => $ssc->id,
                'name' => 'SSC Combined Graduate Level',
                'short_name' => 'SSC CGL',
                'exam_type' => 'GRADUATION',
                'description' => 'Premier exam for Group B & C posts across central government departments.',
                'status' => 'ACTIVE',
            ]
        );

        $sscChsl = Exam::firstOrCreate(
            ['slug' => 'ssc-chsl'],
            [
                'exam_authority_id' => $ssc->id,
                'name' => 'SSC Combined Higher Secondary Level',
                'short_name' => 'SSC CHSL',
                'exam_type' => 'INTERMEDIATE',
                'description' => 'Recruitment for LDC, JSA, and DEO positions.',
                'status' => 'ACTIVE',
            ]
        );

        $sscMts = Exam::firstOrCreate(
            ['slug' => 'ssc-mts'],
            [
                'exam_authority_id' => $ssc->id,
                'name' => 'SSC Multi Tasking Staff',
                'short_name' => 'SSC MTS',
                'exam_type' => 'MATRIC',
                'description' => 'Recruitment for general Central Service Group C Non-Gazetted posts.',
                'status' => 'ACTIVE',
            ]
        );

        // Railway Exams
        $rrbNtpc = Exam::firstOrCreate(
            ['slug' => 'rrb-ntpc'],
            [
                'exam_authority_id' => $railway->id,
                'name' => 'Railway NTPC (Non-Technical Popular Categories)',
                'short_name' => 'Railway NTPC',
                'exam_type' => 'GRADUATION_AND_INTERMEDIATE',
                'description' => 'Recruitment for Station Master, Goods Guard, Commercial Apprentice, and Clerk posts.',
                'status' => 'ACTIVE',
            ]
        );

        $rrbGroupD = Exam::firstOrCreate(
            ['slug' => 'rrb-group-d'],
            [
                'exam_authority_id' => $railway->id,
                'name' => 'Railway Group D',
                'short_name' => 'Railway Group D',
                'exam_type' => 'MATRIC',
                'description' => 'Recruitment for Track Maintainer Grade IV, Helper/Assistant in Indian Railways.',
                'status' => 'ACTIVE',
            ]
        );

        // State Exams
        $upssscPet = Exam::firstOrCreate(
            ['slug' => 'upsssc-pet'],
            [
                'exam_authority_id' => $upsssc->id,
                'name' => 'UPSSSC Preliminary Eligibility Test',
                'short_name' => 'UPSSSC PET',
                'exam_type' => 'ELIGIBILITY',
                'description' => 'Mandatory preliminary screening test for UP Group C recruitment exams.',
                'status' => 'ACTIVE',
            ]
        );

        $bpscPcs = Exam::firstOrCreate(
            ['slug' => 'bpsc-pcs'],
            [
                'exam_authority_id' => $bpsc->id,
                'name' => 'BPSC Combined Competitive Examination',
                'short_name' => 'BPSC CCE',
                'exam_type' => 'STATE_CIVIL_SERVICES',
                'description' => 'State civil services recruitment exam in Bihar.',
                'status' => 'ACTIVE',
            ]
        );

        // 5. Exam Cycles & Stages
        // SSC CGL 2026
        $cgl2026 = ExamCycle::firstOrCreate(
            ['exam_id' => $sscCgl->id, 'year' => 2026],
            [
                'title' => 'SSC CGL Examination 2026',
                'notification_date' => now()->subDays(60),
                'application_start_date' => now()->subDays(50),
                'application_end_date' => now()->subDays(20),
                'exam_start_date' => now()->subDays(10),
                'exam_end_date' => now()->addDays(5),
                'status' => ExamCycleStatus::ACTIVE,
            ]
        );

        $cglTier1 = ExamStage::firstOrCreate(
            ['exam_cycle_id' => $cgl2026->id, 'stage_order' => 1],
            [
                'name' => 'Tier 1 (Computer Based Examination)',
                'slug' => 'tier-1',
                'type' => 'CBT',
                'description' => '100 Questions, 200 Total Marks. 2 Marks per correct, 0.50 negative.',
                'status' => 'ACTIVE',
            ]
        );

        $cglTier2 = ExamStage::firstOrCreate(
            ['exam_cycle_id' => $cgl2026->id, 'stage_order' => 2],
            [
                'name' => 'Tier 2 (Mains Examination)',
                'slug' => 'tier-2',
                'type' => 'CBT',
                'description' => 'Mathematical Abilities, Reasoning, English Language, General Awareness & Computer Knowledge.',
                'status' => 'ACTIVE',
            ]
        );

        // UPSSSC PET 2026 (Single stage exam cycle!)
        $pet2026 = ExamCycle::firstOrCreate(
            ['exam_id' => $upssscPet->id, 'year' => 2026],
            [
                'title' => 'UPSSSC PET 2026',
                'notification_date' => now()->subDays(40),
                'application_start_date' => now()->subDays(30),
                'application_end_date' => now()->subDays(10),
                'exam_start_date' => now()->subDays(2),
                'exam_end_date' => now()->addDays(2),
                'status' => ExamCycleStatus::ACTIVE,
            ]
        );

        $petStage = ExamStage::firstOrCreate(
            ['exam_cycle_id' => $pet2026->id, 'stage_order' => 1],
            [
                'name' => 'Written Examination',
                'slug' => 'written-exam',
                'type' => 'OFFLINE_OMR',
                'description' => '100 Questions, 100 Total Marks. 1 Mark per correct, 0.25 negative.',
                'status' => 'ACTIVE',
            ]
        );

        // Railway NTPC 2026
        $ntpc2026 = ExamCycle::firstOrCreate(
            ['exam_id' => $rrbNtpc->id, 'year' => 2026],
            [
                'title' => 'RRB NTPC Recruitment 2026',
                'notification_date' => now()->subDays(45),
                'application_start_date' => now()->subDays(35),
                'application_end_date' => now()->subDays(15),
                'exam_start_date' => now()->subDays(5),
                'exam_end_date' => now()->addDays(15),
                'status' => ExamCycleStatus::ACTIVE,
            ]
        );

        $ntpcCbt1 = ExamStage::firstOrCreate(
            ['exam_cycle_id' => $ntpc2026->id, 'stage_order' => 1],
            [
                'name' => 'CBT Stage 1',
                'slug' => 'cbt-1',
                'type' => 'CBT',
                'description' => '100 Questions, 100 Total Marks. 1 Mark per correct, 1/3 negative.',
                'status' => 'ACTIVE',
            ]
        );

        // 6. Shifts for CGL Tier 1 & PET
        $shift1 = Shift::firstOrCreate(
            ['exam_stage_id' => $cglTier1->id, 'shift_date' => now()->subDays(5)->format('Y-m-d'), 'name' => 'Shift 1 (09:00 AM - 10:00 AM)'],
            ['start_time' => '09:00:00', 'end_time' => '10:00:00', 'status' => 'ACTIVE']
        );

        $shift2 = Shift::firstOrCreate(
            ['exam_stage_id' => $cglTier1->id, 'shift_date' => now()->subDays(5)->format('Y-m-d'), 'name' => 'Shift 2 (12:30 PM - 01:30 PM)'],
            ['start_time' => '12:30:00', 'end_time' => '13:30:00', 'status' => 'ACTIVE']
        );

        // 7. Prediction Models
        $cglTier1Model = PredictionModel::firstOrCreate(
            ['exam_stage_id' => $cglTier1->id, 'version' => 'v1.0'],
            [
                'name' => 'SSC CGL 2026 Tier 1 Scoring Engine',
                'total_marks' => 200.00,
                'negative_marking_ratio' => 0.25, // 0.50 negative out of 2 marks = 25% ratio
                'formula_config' => ['marks_per_question' => 2.0, 'negative_marks_per_question' => 0.50],
                'status' => 'ACTIVE',
            ]
        );

        $petModel = PredictionModel::firstOrCreate(
            ['exam_stage_id' => $petStage->id, 'version' => 'v1.0'],
            [
                'name' => 'UPSSSC PET 2026 Scoring Engine',
                'total_marks' => 100.00,
                'negative_marking_ratio' => 0.25,
                'formula_config' => ['marks_per_question' => 1.0, 'negative_marks_per_question' => 0.25],
                'status' => 'ACTIVE',
            ]
        );

        $ntpcModel = PredictionModel::firstOrCreate(
            ['exam_stage_id' => $ntpcCbt1->id, 'version' => 'v1.0'],
            [
                'name' => 'RRB NTPC 2026 CBT 1 Engine',
                'total_marks' => 100.00,
                'negative_marking_ratio' => 0.33,
                'formula_config' => ['marks_per_question' => 1.0, 'negative_marks_per_question' => 0.33],
                'status' => 'ACTIVE',
            ]
        );

        // 8. Generate Realistic Crowd Submissions for Ranking
        $this->seedCrowdSubmissions($cglTier1Model, $cglTier1, $shift1, $categories);
        $this->seedCrowdSubmissions($petModel, $petStage, null, $categories);

        // 9. Notices
        Notice::firstOrCreate(
            ['title' => 'SSC CGL 2026 Tier 1 Answer Key Released'],
            [
                'exam_id' => $sscCgl->id,
                'exam_cycle_id' => $cgl2026->id,
                'exam_stage_id' => $cglTier1->id,
                'slug' => 'ssc-cgl-2026-tier-1-answer-key-released',
                'notice_type' => 'ANSWER_KEY',
                'notice_date' => now()->subDays(2),
                'official_url' => 'https://ssc.gov.in/answer-keys',
                'content' => 'Staff Selection Commission has uploaded the tentative Answer Keys along with Candidates Response Sheets for Combined Graduate Level Examination 2026 Tier 1.',
                'is_important' => true,
                'published_at' => now()->subDays(2),
                'status' => 'ACTIVE',
            ]
        );

        Notice::firstOrCreate(
            ['title' => 'UPSSSC PET 2026 Examination Date Notice'],
            [
                'exam_id' => $upssscPet->id,
                'exam_cycle_id' => $pet2026->id,
                'exam_stage_id' => $petStage->id,
                'slug' => 'upsssc-pet-2026-exam-date-notice',
                'notice_type' => 'EXAM_DATE',
                'notice_date' => now()->subDays(5),
                'official_url' => 'https://upsssc.gov.in/notices',
                'content' => 'Official examination date schedule for Preliminary Eligibility Test 2026 has been published by UPSSSC.',
                'is_important' => true,
                'published_at' => now()->subDays(5),
                'status' => 'ACTIVE',
            ]
        );

        Notice::firstOrCreate(
            ['title' => 'RRB NTPC 2026 CBT 1 City Intimation Link Active'],
            [
                'exam_id' => $rrbNtpc->id,
                'exam_cycle_id' => $ntpc2026->id,
                'exam_stage_id' => $ntpcCbt1->id,
                'slug' => 'rrb-ntpc-2026-cbt-1-city-intimation',
                'notice_type' => 'ADMIT_CARD',
                'notice_date' => now()->subDays(8),
                'official_url' => 'https://indianrailways.gov.in/rrb-ntpc',
                'content' => 'Candidates appearing for RRB NTPC CBT Stage 1 can check their exam city intimation and travel pass details using official login.',
                'is_important' => false,
                'published_at' => now()->subDays(8),
                'status' => 'ACTIVE',
            ]
        );

        // 10. Important Links
        ImportantLink::firstOrCreate(
            ['title' => 'SSC Official Portal'],
            [
                'exam_id' => $sscCgl->id,
                'url' => 'https://ssc.gov.in',
                'link_type' => 'OFFICIAL_WEBSITE',
                'is_external' => true,
                'sort_order' => 1,
                'status' => 'ACTIVE',
            ]
        );

        ImportantLink::firstOrCreate(
            ['title' => 'UPSSSC Official Website'],
            [
                'exam_id' => $upssscPet->id,
                'url' => 'https://upsssc.gov.in',
                'link_type' => 'OFFICIAL_WEBSITE',
                'is_external' => true,
                'sort_order' => 2,
                'status' => 'ACTIVE',
            ]
        );

        ImportantLink::firstOrCreate(
            ['title' => 'Railway NTPC Admit Card Download'],
            [
                'exam_id' => $rrbNtpc->id,
                'exam_cycle_id' => $ntpc2026->id,
                'url' => 'https://indianrailways.gov.in/admit-card',
                'link_type' => 'ADMIT_CARD',
                'is_external' => true,
                'sort_order' => 3,
                'status' => 'ACTIVE',
            ]
        );

        ImportantLink::firstOrCreate(
            ['title' => 'SSC CGL 2026 Answer Key Portal'],
            [
                'exam_id' => $sscCgl->id,
                'exam_cycle_id' => $cgl2026->id,
                'url' => 'https://ssc.gov.in/answer-key-login',
                'link_type' => 'ANSWER_KEY',
                'is_external' => true,
                'sort_order' => 4,
                'status' => 'ACTIVE',
            ]
        );
    }

    private function seedCrowdSubmissions(PredictionModel $model, ExamStage $stage, ?Shift $shift, array $categories): void
    {
        $catKeys = array_keys($categories);
        $totalMax = (float) $model->total_marks;

        for ($i = 1; $i <= 50; $i++) {
            $catCode = $catKeys[$i % count($catKeys)];
            $category = $categories[$catCode];

            // Generate realistic scores across distribution curve
            $attempted = rand(70, 98);
            $correct = rand(45, $attempted);
            $incorrect = $attempted - $correct;

            $marksPerQuestion = $totalMax > 150 ? 2.0 : 1.0;
            $negativeMarks = $marksPerQuestion * (float) $model->negative_marking_ratio;
            $rawScore = round(($correct * $marksPerQuestion) - ($incorrect * $negativeMarks), 2);

            $submission = CandidateSubmission::create([
                'prediction_model_id' => $model->id,
                'exam_stage_id' => $stage->id,
                'shift_id' => $shift?->id,
                'category_id' => $category->id,
                'candidate_identifier' => 'STUDENT_'.(1000 + $i),
                'total_attempted' => $attempted,
                'correct_answers' => $correct,
                'incorrect_answers' => $incorrect,
                'raw_score' => $rawScore,
                'normalized_score' => $rawScore,
                'session_token' => Str::uuid()->toString(),
                'ip_hash' => hash('sha256', '127.0.0.'.rand(1, 250)),
                'user_agent_hash' => hash('sha256', 'Mozilla/5.0'),
                'device_fingerprint' => hash('sha256', 'device_'.$i),
                'risk_score' => 0,
                'trust_status' => SubmissionTrustStatus::TRUSTED,
                'submitted_at' => now()->subMinutes(rand(10, 1000)),
            ]);

            PredictionResult::create([
                'candidate_submission_id' => $submission->id,
                'predicted_rank_overall' => rand(1, 100),
                'predicted_rank_category' => rand(1, 30),
                'percentile' => round(rand(6000, 9999) / 100, 2),
                'confidence_score' => 95.00,
                'metadata' => ['seeded' => true],
                'calculated_at' => now(),
            ]);
        }
    }
}
