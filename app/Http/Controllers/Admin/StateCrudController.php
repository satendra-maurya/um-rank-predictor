<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ActiveStatus;
use App\Http\Requests\Admin\StateRequest;
use App\Models\State;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class StateCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup(): void
    {
        $this->crud->setModel(State::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/state');
        $this->crud->setEntityNameStrings('state', 'states');
    }

    protected function setupListOperation(): void
    {
        $this->crud->column('id')->label('ID');
        $this->crud->column('name')->label('State Name');
        $this->crud->column('code')->label('Code');
        $this->crud->column('status')->label('Status')->type('enum')->enum_class(ActiveStatus::class)->enum_function('label');
        $this->crud->column('created_at')->label('Created At');
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation(StateRequest::class);

        $this->crud->field('name')->label('State Name')->type('text');
        $this->crud->field('code')->label('State Code (e.g. UP, BR)')->type('text');
        $this->crud->field('status')->label('Status')->type('enum')->enum_class(ActiveStatus::class)->enum_function('label');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
