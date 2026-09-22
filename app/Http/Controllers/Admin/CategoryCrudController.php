<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActiveStatus;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class CategoryCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup(): void
    {
        $this->crud->setModel(Category::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/category');
        $this->crud->setEntityNameStrings('category', 'categories');
    }

    protected function setupListOperation(): void
    {
        $this->crud->column('id')->label('ID');
        $this->crud->column('name')->label('Category Name');
        $this->crud->column('code')->label('Code');
        $this->crud->column('description')->label('Description');
        $this->crud->column('status')->label('Status')->type('enum')->enum_class(ActiveStatus::class)->enum_function('label');
        $this->crud->column('created_at')->label('Created At');
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation(CategoryRequest::class);

        $this->crud->field('name')->label('Category Name (e.g. Unreserved)')->type('text');
        $this->crud->field('code')->label('Category Code (e.g. UR, OBC, EWS)')->type('text');
        $this->crud->field('description')->label('Description')->type('textarea');
        $this->crud->field('status')->label('Status')->type('enum')->enum_class(ActiveStatus::class)->enum_function('label');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
