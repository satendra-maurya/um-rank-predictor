<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SubmissionTrustStatus;
use App\Http\Requests\Admin\CandidateSubmissionRequest;
use App\Models\CandidateSubmission;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class CandidateSubmissionCrudController extends CrudController
{
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup(): void
    {
        $this->crud->setModel(CandidateSubmission::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/candidate-submission');
        $this->crud->setEntityNameStrings('candidate submission', 'candidate submissions');
    }

    protected function setupListOperation(): void
    {
        $this->crud->column('id')->label('ID');
        $this->crud->column('candidate_identifier')->label('Roll Number');
        $this->crud->column('dob')->label('Date of Birth')->type('date');
        $this->crud->column('exam_stage_id')->label('Exam Stage')->type('relationship')->attribute('name');
        $this->crud->column('category_id')->label('Category')->type('relationship')->attribute('code');
        $this->crud->column('gender')->label('Gender');
        $this->crud->column('raw_score')->label('Marks Obtained');
        $this->crud->column('risk_score')->label('Risk Score');
        $this->crud->column('trust_status')->label('Trust Status')->type('enum')->enum_class(SubmissionTrustStatus::class)->enum_function('label');
        $this->crud->column('submitted_at')->label('Submitted At')->type('datetime');
    }

    protected function setupShowOperation(): void
    {
        $this->crud->column('id')->label('Submission ID');
        $this->crud->column('candidate_identifier')->label('Roll Number');
        $this->crud->column('dob')->label('Date of Birth')->type('date');
        $this->crud->column('raw_score')->label('Marks Obtained');
        $this->crud->column('gender')->label('Gender');
        $this->crud->column('exam_stage_id')->label('Exam Stage')->type('relationship')->attribute('name');
        $this->crud->column('shift_id')->label('Shift')->type('relationship')->attribute('name');
        $this->crud->column('category_id')->label('Category')->type('relationship')->attribute('name');
        $this->crud->column('total_attempted')->label('Total Attempted');
        $this->crud->column('correct_answers')->label('Correct Answers');
        $this->crud->column('incorrect_answers')->label('Incorrect Answers');
        $this->crud->column('risk_score')->label('Risk Score');
        $this->crud->column('trust_status')->label('Trust Status')->type('enum')->enum_class(SubmissionTrustStatus::class)->enum_function('label');
        $this->crud->column('submitted_at')->label('Submitted At')->type('datetime');
    }

    protected function setupUpdateOperation(): void
    {
        $this->crud->setValidation(CandidateSubmissionRequest::class);

        $this->crud->field('candidate_identifier')->label('Roll Number')->type('text')->attributes(['disabled' => 'disabled']);
        $this->crud->field('dob')->label('Date of Birth')->type('date')->attributes(['disabled' => 'disabled']);
        $this->crud->field('raw_score')->label('Marks Obtained')->type('number')->attributes(['disabled' => 'disabled']);
        $this->crud->field('trust_status')->label('Trust Status')->type('enum')->enum_class(SubmissionTrustStatus::class)->enum_function('label');
        $this->crud->field('risk_score')->label('Risk Score')->type('number');
    }
}
