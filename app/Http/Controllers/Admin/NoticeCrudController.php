<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\NoticeRequest;
use App\Models\Notice;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class NoticeCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation;
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup(): void
    {
        $this->crud->setModel(Notice::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/notice');
        $this->crud->setEntityNameStrings('notice', 'notices');
    }

    protected function setupListOperation(): void
    {
        $this->crud->column('id')->label('ID');
        $this->crud->column('title')->label('Notice Title');
        $this->crud->column('notice_date')->label('Notice Date')->type('date');
        $this->crud->column('published_at')->label('Published At')->type('datetime');
        $this->crud->column('status')->label('Status')->type('badge')->options([
            'DRAFT' => 'warning',
            'ACTIVE' => 'success',
            'INACTIVE' => 'danger',
        ]);
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation(NoticeRequest::class);

        $this->crud->field('title')->label('Notice Title')->type('text');
        $this->crud->field('slug')->label('Slug')->type('text');
        $this->crud->field('content')->label('Notice Content')->type('textarea');
        $this->crud->field('notice_date')->label('Notice Date')->type('date')->default(date('Y-m-d'));
        $this->crud->field('published_at')->label('Publish Date & Time')->type('datetime');
        $this->crud->field('link_url')->label('External Link URL')->type('url');
        $this->crud->field('status')->label('Status')->type('select_from_array')->options([
            'DRAFT' => 'Draft',
            'ACTIVE' => 'Active',
            'INACTIVE' => 'Inactive',
        ])->default('ACTIVE');
    }

    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }
}
