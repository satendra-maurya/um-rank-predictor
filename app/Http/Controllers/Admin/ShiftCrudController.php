<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActiveStatus;
use App\Http\Requests\Admin\ShiftRequest;
use App\Models\Shift;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class ShiftCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup(): void
    {
        $this->crud->setModel(Shift::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/shift');
        $this->crud->setEntityNameStrings('shift', 'shifts');
    }

    protected function setupListOperation(): void
    {
        $this->crud->column('id')->label('ID');
        $this->crud->column('exam_stage_id')->label('Exam Stage')->type('relationship')->attribute('name');
        $this->crud->column('name')->label('Shift Name');
        $this->crud->column('shift_date')->label('Shift Date')->type('date');
        $this->crud->column('start_time')->label('Start Time');
        $this->crud->column('end_time')->label('End Time');
        $this->crud->column('status')->label('Status')->type('enum')->enum_class(ActiveStatus::class)->enum_function('label');
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation(ShiftRequest::class);

        $this->crud->field('exam_stage_id')->label('Exam Stage')->type('select')->entity('examStage')->attribute('name');
        $this->crud->field('name')->label('Shift Name (e.g. Shift 1)')->type('text');
        $this->crud->field('shift_date')->label('Shift Date')->type('date');
        $this->crud->field('start_time')->label('Start Time')->type('time');
        $this->crud->field('end_time')->label('End Time')->type('time');
        $this->crud->field('status')->label('Status')->type('enum')->enum_class(ActiveStatus::class)->enum_function('label');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
