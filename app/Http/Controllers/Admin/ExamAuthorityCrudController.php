<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ExamAuthorityRequest;
use App\Models\ExamAuthority;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class ExamAuthorityCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup(): void
    {
        $this->crud->setModel(ExamAuthority::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/exam-authority');
        $this->crud->setEntityNameStrings('exam authority', 'exam authorities');
    }

    protected function setupListOperation(): void
    {
        $this->crud->column('id')->label('ID');
        $this->crud->column('name')->label('Authority Name');
        $this->crud->column('code')->label('Code');
        $this->crud->column('level')->label('Level')->type('badge')->options([
            'NATIONAL' => 'primary',
            'STATE' => 'info',
        ]);
        $this->crud->column('state_id')->label('State')->type('relationship')->attribute('name');
        $this->crud->column('status')->label('Status')->type('badge')->options([
            'ACTIVE' => 'success',
            'INACTIVE' => 'danger',
        ]);
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation(ExamAuthorityRequest::class);

        $this->crud->field('name')->label('Authority Name (e.g. Staff Selection Commission)')->type('text');
        $this->crud->field('slug')->label('Slug')->type('text');
        $this->crud->field('code')->label('Code (e.g. SSC, RRB, UPSSSC)')->type('text');
        $this->crud->field('level')->label('Authority Level')->type('select_from_array')->options([
            'NATIONAL' => 'National Level',
            'STATE' => 'State Level',
        ])->default('NATIONAL');
        $this->crud->field('state_id')->label('State (if State Level)')->type('select')->entity('state')->attribute('name')->allows_null(true);
        $this->crud->field('website_url')->label('Official Website URL')->type('url');
        $this->crud->field('description')->label('Description')->type('textarea');
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
