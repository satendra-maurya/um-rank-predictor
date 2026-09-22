<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CutoffRequest;
use App\Models\Cutoff;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class CutoffCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup(): void
    {
        $this->crud->setModel(Cutoff::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/cutoff');
        $this->crud->setEntityNameStrings('cutoff', 'cutoffs');
    }

    protected function setupListOperation(): void
    {
        $this->crud->column('id')->label('ID');
        $this->crud->column('exam_stage_id')->label('Exam Stage')->type('relationship')->attribute('name');
        $this->crud->column('category_id')->label('Category')->type('relationship')->attribute('code');
        $this->crud->column('year')->label('Year');
        $this->crud->column('cutoff_marks')->label('Cutoff Marks');
        $this->crud->column('notes')->label('Notes');
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation(CutoffRequest::class);

        $this->crud->field('exam_stage_id')->label('Exam Stage')->type('select')->entity('examStage')->attribute('name');
        $this->crud->field('category_id')->label('Category')->type('select')->entity('category')->attribute('code');
        $this->crud->field('year')->label('Year')->type('number')->default(date('Y'));
        $this->crud->field('cutoff_marks')->label('Cutoff Marks')->type('number')->attributes(['step' => '0.01']);
        $this->crud->field('notes')->label('Notes')->type('textarea');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
