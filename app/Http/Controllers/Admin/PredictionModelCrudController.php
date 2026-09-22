<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActiveStatus;
use App\Http\Requests\Admin\PredictionModelRequest;
use App\Models\PredictionModel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class PredictionModelCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup(): void
    {
        $this->crud->setModel(PredictionModel::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/prediction-model');
        $this->crud->setEntityNameStrings('prediction model', 'prediction models');
    }

    protected function setupListOperation(): void
    {
        $this->crud->column('id')->label('ID');
        $this->crud->column('exam_stage_id')->label('Exam Stage')->type('relationship')->attribute('name');
        $this->crud->column('name')->label('Model Name');
        $this->crud->column('version')->label('Version');
        $this->crud->column('algorithm_type')->label('Algorithm');
        $this->crud->column('status')->label('Status')->type('enum')->enum_class(ActiveStatus::class)->enum_function('label');
        $this->crud->column('published_at')->label('Published At')->type('datetime');
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation(PredictionModelRequest::class);

        $this->crud->field('exam_stage_id')->label('Exam Stage')->type('select')->entity('examStage')->attribute('name');
        $this->crud->field('name')->label('Model Name')->type('text');
        $this->crud->field('version')->label('Version (e.g. v1.0)')->type('text');
        $this->crud->field('algorithm_type')->label('Algorithm Type (e.g. RAW_RANK_PERCENTILE)')->type('text');
        $this->crud->field('config_json')->label('Configuration JSON')->type('textarea');
        $this->crud->field('status')->label('Status')->type('enum')->enum_class(ActiveStatus::class)->enum_function('label');
        $this->crud->field('published_at')->label('Published At')->type('datetime');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
