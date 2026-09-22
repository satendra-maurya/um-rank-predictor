<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ImportantLinkRequest;
use App\Models\ImportantLink;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class ImportantLinkCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup(): void
    {
        $this->crud->setModel(ImportantLink::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/important-link');
        $this->crud->setEntityNameStrings('important link', 'important links');
    }

    protected function setupListOperation(): void
    {
        $this->crud->column('id')->label('ID');
        $this->crud->column('title')->label('Link Title');
        $this->crud->column('url')->label('URL');
        $this->crud->column('display_order')->label('Display Order');
        $this->crud->column('is_open_in_new_tab')->label('New Tab')->type('boolean');
        $this->crud->column('status')->label('Status')->type('badge')->options([
            'ACTIVE' => 'success',
            'INACTIVE' => 'danger',
        ]);
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation(ImportantLinkRequest::class);

        $this->crud->field('title')->label('Link Title')->type('text');
        $this->crud->field('url')->label('URL')->type('url');
        $this->crud->field('display_order')->label('Display Order')->type('number')->default(0);
        $this->crud->field('is_open_in_new_tab')->label('Open in New Tab')->type('checkbox')->default(true);
        $this->crud->field('status')->label('Status')->type('select_from_array')->options([
            'ACTIVE' => 'Active',
            'INACTIVE' => 'Inactive',
        ])->default('ACTIVE');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
