<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_pages(): void
    {
        $response = $this->get('/admin/all-tasks');
        $response->assertRedirect('/login');

        $response = $this->get('/admin/users-summary');
        $response->assertRedirect('/login');
    }

    public function test_regular_user_cannot_access_admin_pages(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/admin/all-tasks');
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get('/admin/users-summary');
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get('/admin/all-tasks/export');
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get('/admin/users-summary/export');
        $response->assertStatus(403);
    }

    public function test_regular_user_does_not_see_admin_menu(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertDontSee('Menu Administrator');
        $response->assertDontSee('Semua Tugas Pegawai');
    }

    public function test_admin_can_access_all_tasks_page_and_sees_menu(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Menu Administrator');

        $response = $this->actingAs($admin)->get('/admin/all-tasks');
        $response->assertStatus(200);
        $response->assertSee('Semua Tugas Pegawai');
    }

    public function test_admin_can_access_users_summary_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/users-summary');
        $response->assertStatus(200);
        $response->assertSee('Rekapitulasi Penggunaan');
    }
}
