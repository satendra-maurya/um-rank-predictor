<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ExamCycleStatus;
use App\Http\Requests\Admin\ExamCycleRequest;
use App\Models\ExamCycle;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class ExamCycleCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup(): void
    {
        $this->crud->setModel(ExamCycle::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/exam-cycle');
        $this->crud->setEntityNameStrings('exam cycle', 'exam cycles');
    }

    protected function setupListOperation(): void
    {
        $this->crud->column('id')->label('ID');
        $this->crud->column('exam_id')->label('Exam')->type('relationship')->attribute('name');
        $this->crud->column('title')->label('Cycle Title');
        $this->crud->column('year')->label('Year');
        $this->crud->column('exam_start_date')->label('Exam Start Date')->type('date');
        $this->crud->column('status')->label('Status')->type('enum')->enum_class(ExamCycleStatus::class)->enum_function('label');
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation(ExamCycleRequest::class);

        $this->crud->field('exam_id')->label('Exam')->type('select')->entity('exam')->attribute('name');
        $this->crud->field('title')->label('Cycle Title (e.g. SSC CGL 2026 Recruitment)')->type('text');
        $this->crud->field('year')->label('Year')->type('number')->default(date('Y'));
        $this->crud->field('notification_date')->label('Notification Release Date')->type('date');
        $this->crud->field('application_start_date')->label('Application Start Date')->type('date');
        $this->crud->field('application_end_date')->label('Application End Date')->type('date');
        $this->crud->field('exam_start_date')->label('Exam Start Date')->type('date');
        $this->crud->field('exam_end_date')->label('Exam End Date')->type('date');
        $this->crud->field('result_date')->label('Result Date')->type('date');
        $this->crud->field('status')->label('Status')->type('enum')->enum_class(ExamCycleStatus::class)->enum_function('label');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
