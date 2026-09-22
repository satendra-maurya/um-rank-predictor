<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\VacancyRequest;
use App\Models\Vacancy;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class VacancyCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup(): void
    {
        $this->crud->setModel(Vacancy::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/vacancy');
        $this->crud->setEntityNameStrings('vacancy', 'vacancies');
    }

    protected function setupListOperation(): void
    {
        $this->crud->column('id')->label('ID');
        $this->crud->column('exam_cycle_id')->label('Exam Cycle')->type('relationship')->attribute('title');
        $this->crud->column('category_id')->label('Category')->type('relationship')->attribute('code');
        $this->crud->column('post_name')->label('Post Name');
        $this->crud->column('vacancy_count')->label('Vacancy Count');
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation(VacancyRequest::class);

        $this->crud->field('exam_cycle_id')->label('Exam Cycle')->type('select')->entity('examCycle')->attribute('title');
        $this->crud->field('category_id')->label('Category')->type('select')->entity('category')->attribute('code');
        $this->crud->field('post_name')->label('Post Name (e.g. Assistant Section Officer)')->type('text');
        $this->crud->field('vacancy_count')->label('Vacancy Count')->type('number');
        $this->crud->field('remarks')->label('Remarks')->type('textarea');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
