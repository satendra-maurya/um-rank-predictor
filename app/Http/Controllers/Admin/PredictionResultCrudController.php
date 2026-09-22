<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PredictionResultRequest;
use App\Models\PredictionResult;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class PredictionResultCrudController extends CrudController
{
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup(): void
    {
        $this->crud->setModel(PredictionResult::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/prediction-result');
        $this->crud->setEntityNameStrings('prediction result', 'prediction results');
    }

    protected function setupListOperation(): void
    {
        $this->crud->column('id')->label('ID');
        $this->crud->column('candidate_submission_id')->label('Candidate')->type('relationship')->attribute('name');
        $this->crud->column('predicted_rank_overall')->label('Overall Rank');
        $this->crud->column('predicted_rank_category')->label('Category Rank');
        $this->crud->column('percentile')->label('Percentile');
        $this->crud->column('confidence_score')->label('Confidence Score');
        $this->crud->column('calculated_at')->label('Calculated At')->type('datetime');
    }

    protected function setupShowOperation(): void
    {
        $this->crud->column('id')->label('Result ID');
        $this->crud->column('candidate_submission_id')->label('Candidate Submission')->type('relationship')->attribute('name');
        $this->crud->column('predicted_rank_overall')->label('Overall Rank');
        $this->crud->column('predicted_rank_category')->label('Category Rank');
        $this->crud->column('percentile')->label('Percentile');
        $this->crud->column('confidence_score')->label('Confidence Score');
        $this->crud->column('calculated_at')->label('Calculated At')->type('datetime');
    }

    protected function setupUpdateOperation(): void
    {
        $this->crud->setValidation(PredictionResultRequest::class);

        $this->crud->field('predicted_rank_overall')->label('Overall Rank')->type('number');
        $this->crud->field('predicted_rank_category')->label('Category Rank')->type('number');
        $this->crud->field('percentile')->label('Percentile')->type('number')->attributes(['step' => '0.01']);
        $this->crud->field('confidence_score')->label('Confidence Score')->type('number')->attributes(['step' => '0.01']);
    }
}
