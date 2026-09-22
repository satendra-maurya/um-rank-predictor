<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActiveStatus;
use App\Http\Requests\Admin\ExamRequest;
use App\Models\Exam;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class ExamCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup(): void
    {
        $this->crud->setModel(Exam::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/exam');
        $this->crud->setEntityNameStrings('exam', 'exams');
    }

    protected function setupListOperation(): void
    {
        $this->crud->column('id')->label('ID');
        $this->crud->column('name')->label('Exam Name');
        $this->crud->column('code')->label('Code');
        $this->crud->column('exam_authority_id')->label('Exam Authority')->type('relationship')->attribute('name');
        $this->crud->column('status')->label('Status')->type('enum')->enum_class(ActiveStatus::class)->enum_function('label');
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation(ExamRequest::class);

        $this->crud->field('name')->label('Exam Name')->type('text');
        $this->crud->field('slug')->label('Slug')->type('text');
        $this->crud->field('code')->label('Exam Code (e.g. CGL, NTPC)')->type('text');
        $this->crud->field('exam_authority_id')->label('Exam Authority')->type('select')->entity('examAuthority')->attribute('name');
        $this->crud->field('description')->label('Description')->type('textarea');
        $this->crud->field('status')->label('Status')->type('enum')->enum_class(ActiveStatus::class)->enum_function('label');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
