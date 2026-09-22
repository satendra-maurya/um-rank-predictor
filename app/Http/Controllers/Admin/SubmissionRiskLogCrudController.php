<?php

namespace App\Http\Controllers\Admin;

use App\Models\SubmissionRiskLog;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

class SubmissionRiskLogCrudController extends CrudController
{
    use ListOperation;
    use ShowOperation;

    public function setup(): void
    {
        $this->crud->setModel(SubmissionRiskLog::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/submission-risk-log');
        $this->crud->setEntityNameStrings('risk log', 'risk logs');
    }

    protected function setupListOperation(): void
    {
        $this->crud->column('id')->label('ID');
        $this->crud->column('candidate_submission_id')->label('Candidate Submission')->type('relationship')->attribute('name');
        $this->crud->column('risk_factor')->label('Risk Factor');
        $this->crud->column('risk_weight')->label('Risk Weight');
        $this->crud->column('created_at')->label('Triggered At')->type('datetime');
    }

    protected function setupShowOperation(): void
    {
        $this->crud->column('id')->label('Log ID');
        $this->crud->column('candidate_submission_id')->label('Candidate Submission')->type('relationship')->attribute('name');
        $this->crud->column('risk_factor')->label('Risk Factor');
        $this->crud->column('risk_weight')->label('Risk Weight');
        $this->crud->column('details')->label('Details');
        $this->crud->column('created_at')->label('Triggered At')->type('datetime');
    }
}
