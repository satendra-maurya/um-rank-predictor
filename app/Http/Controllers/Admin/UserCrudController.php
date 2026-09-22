<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

class UserCrudController extends CrudController
{
    use CreateOperation;
    use DeleteOperation { destroy as traitDestroy; }
    use ListOperation;
    use ShowOperation;
    use UpdateOperation;

    public function setup(): void
    {
        $this->crud->setModel(User::class);
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/user');
        $this->crud->setEntityNameStrings('user', 'users');
    }

    protected function setupListOperation(): void
    {
        $this->crud->column('id')->label('ID');
        $this->crud->column('name')->label('Name');
        $this->crud->column('email')->label('Email');
        $this->crud->column('is_admin')->label('Role')->type('badge')->options([
            true => 'danger',
            false => 'secondary',
        ])->enum([
            true => 'Admin',
            false => 'User',
        ]);
        $this->crud->column('created_at')->label('Joined At')->type('datetime');
    }

    protected function setupCreateOperation(): void
    {
        $this->crud->setValidation(UserRequest::class);

        $this->crud->field('name')->label('Full Name')->type('text');
        $this->crud->field('email')->label('Email Address')->type('email');
        $this->crud->field('password')->label('Password')->type('password');
        $this->crud->field('is_admin')->label('Is Administrator')->type('checkbox');
    }

    protected function setupUpdateOperation(): void
    {
        $this->crud->setValidation(UserRequest::class);

        $this->crud->field('name')->label('Full Name')->type('text');
        $this->crud->field('email')->label('Email Address')->type('email');
        $this->crud->field('password')->label('Password (leave blank to keep current)')->type('password');
        $this->crud->field('is_admin')->label('Is Administrator')->type('checkbox');
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        if ((int) $id === (int) backpack_user()->id) {
            return response()->json([
                'type' => 'error',
                'message' => 'You cannot delete your own logged-in admin account.',
            ], 403);
        }

        return $this->traitDestroy($id);
    }
}
