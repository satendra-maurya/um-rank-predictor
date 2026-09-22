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

    public ?int $category_id = null;

    public string $gender = 'Male';

    public ?int $total_questions = 100;

    public ?int $correct_answers = 75;

    public ?int $incorrect_answers = 25;

    public ?int $shift_id = null;

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

    public function selectCategory(string $categorySlug): void
    {
        $this->selectedCategorySlug = strtolower($categorySlug);
        $this->selectedStateId = null;
        $this->selectedAuthorityId = null;
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

    public function resetPredictor(): void
    {
        $this->step = 1;
        $this->selectedCategorySlug = null;
        $this->selectedStateId = null;
        $this->selectedAuthorityId = null;
        $this->selectedExamId = null;
        $this->selectedCycleId = null;
        $this->selectedStageId = null;
        $this->predictionResult = null;
        $this->isAnalyzing = false;
    }

    public function submitPrediction(PredictionService $service): void
    {
        $this->validate([
            'name' => 'required|string|min:2|max:100',
            'category_id' => 'required|exists:categories,id',
            'gender' => 'required|in:Male,Female,Other',
            'total_questions' => 'required|integer|min:1|max:1000',
            'correct_answers' => 'required|integer|min:0',
            'incorrect_answers' => 'required|integer|min:0',
            'shift_id' => 'nullable|exists:shifts,id',
        ]);

        if (($this->correct_answers + $this->incorrect_answers) > $this->total_questions) {
            $this->addError('correct_answers', 'Correct + Incorrect answers cannot exceed total questions ('.$this->total_questions.').');

            return;
        }

        $model = PredictionModel::where('exam_stage_id', $this->selectedStageId)
            ->where('status', ActiveStatus::ACTIVE)
            ->first();

        if (! $model) {
            // Fallback or auto-create prediction model for stage
            $model = PredictionModel::firstOrCreate(
                ['exam_stage_id' => $this->selectedStageId, 'version' => 'v1.0'],
                [
                    'name' => 'Default Prediction Engine',
                    'total_marks' => $this->total_questions * 1.0,
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
            'gender' => $this->gender,
            'total_questions' => $this->total_questions,
            'correct_answers' => $this->correct_answers,
            'incorrect_answers' => $this->incorrect_answers,
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
        ]);
    }
}
