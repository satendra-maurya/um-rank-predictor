<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActiveStatus;
use App\Http\Requests\Admin\ExamStageRequest;
use App\Models\ExamStage;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class ExamStageCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup(): void
    {
        $this->crud->setModel(ExamStage::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/exam-stage');
        $this->crud->setEntityNameStrings('exam stage', 'exam stages');
    }

    protected function setupListOperation(): void
    {
        $this->crud->column('id')->label('ID');
        $this->crud->column('exam_cycle_id')->label('Exam Cycle')->type('relationship')->attribute('title');
        $this->crud->column('name')->label('Stage Name');
        $this->crud->column('stage_order')->label('Order');
        $this->crud->column('total_marks')->label('Total Marks');
        $this->crud->column('negative_marking_ratio')->label('Negative Ratio');
        $this->crud->column('status')->label('Status')->type('enum')->enum_class(ActiveStatus::class)->enum_function('label');
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation(ExamStageRequest::class);

        $this->crud->field('exam_cycle_id')->label('Exam Cycle')->type('select')->entity('examCycle')->attribute('title');
        $this->crud->field('name')->label('Stage Name (e.g. Tier 1, CBT 1)')->type('text');
        $this->crud->field('slug')->label('Slug')->type('text');
        $this->crud->field('stage_order')->label('Stage Order Number')->type('number')->default(1);
        $this->crud->field('total_marks')->label('Total Marks')->type('number')->attributes(['step' => '0.01']);
        $this->crud->field('duration_minutes')->label('Duration (Minutes)')->type('number');
        $this->crud->field('negative_marking_ratio')->label('Negative Marking Ratio (e.g. 0.25, 0.33)')->type('number')->attributes(['step' => '0.01']);
        $this->crud->field('description')->label('Description')->type('textarea');
        $this->crud->field('status')->label('Status')->type('enum')->enum_class(ActiveStatus::class)->enum_function('label');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
