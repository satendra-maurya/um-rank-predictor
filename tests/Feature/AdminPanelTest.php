<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard_to_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/admin/login');
    }

    public function test_non_admin_user_cannot_access_admin_dashboard(): void
    {
        $regularUser = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($regularUser, 'backpack')->get('/admin/dashboard');

        $response->assertRedirect('/admin/login');
    }

    public function test_admin_user_can_access_admin_dashboard(): void
    {
        $adminUser = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($adminUser, 'backpack')->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Exams');
    }

    public function test_admin_user_can_access_crud_index_pages(): void
    {
        $adminUser = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($adminUser, 'backpack')->get('/admin/state')->assertStatus(200);
        $this->actingAs($adminUser, 'backpack')->get('/admin/exam')->assertStatus(200);
        $this->actingAs($adminUser, 'backpack')->get('/admin/exam-cycle')->assertStatus(200);
        $this->actingAs($adminUser, 'backpack')->get('/admin/notice')->assertStatus(200);
        $this->actingAs($adminUser, 'backpack')->get('/admin/candidate-submission')->assertStatus(200);
        $this->actingAs($adminUser, 'backpack')->get('/admin/user')->assertStatus(200);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $adminUser = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($adminUser, 'backpack')->delete('/admin/user/'.$adminUser->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['id' => $adminUser->id]);
    }
}
