<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_are_redirected_to_login(): void
    {
        $response = $this->get('/tasks');
        $response->assertRedirect('/login');

        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_task_list(): void
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Tugas Pertama Saya',
            'priority' => 'tinggi',
            'status' => 'belum dimulai',
        ]);

        $response = $this->actingAs($user)->get('/tasks');
        $response->assertStatus(200);
        $response->assertSee('Tugas Pertama Saya');
    }

    public function test_user_can_create_a_task(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Proyek',
            'slug' => 'proyek',
        ]);

        $response = $this->actingAs($user)->post('/tasks', [
            'title' => 'Membangun fitur manajemen tugas',
            'description' => 'Implementasi CRUD dengan validasi',
            'priority' => 'tinggi',
            'status' => 'belum dimulai',
            'category_id' => $category->id,
            'due_date' => now()->addDays(5)->format('Y-m-d'),
        ]);

        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Membangun fitur manajemen tugas',
            'priority' => 'tinggi',
            'status' => 'belum dimulai',
        ]);
    }

    public function test_task_creation_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/tasks', [
            'title' => '',
            'priority' => 'invalid_priority',
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors(['title', 'priority', 'status']);
    }

    public function test_user_can_view_own_task_detail(): void
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Detail Tugas Penting',
            'description' => 'Rincian deskripsi tugas lengkap',
            'priority' => 'tinggi',
            'status' => 'dikerjakan',
        ]);

        $response = $this->actingAs($user)->get("/tasks/{$task->id}");
        $response->assertStatus(200);
        $response->assertSee('Detail Tugas Penting');
        $response->assertSee('Rincian deskripsi tugas lengkap');
    }

    public function test_user_can_update_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Judul Awal',
            'priority' => 'rendah',
            'status' => 'belum dimulai',
        ]);

        $response = $this->actingAs($user)->put("/tasks/{$task->id}", [
            'title' => 'Judul Telah Diperbarui',
            'priority' => 'tinggi',
            'status' => 'dikerjakan',
        ]);

        $response->assertRedirect("/tasks/{$task->id}");
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Judul Telah Diperbarui',
            'priority' => 'tinggi',
            'status' => 'dikerjakan',
        ]);
    }

    public function test_user_can_delete_own_task(): void
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Tugas Yang Akan Dihapus',
            'priority' => 'rendah',
            'status' => 'belum dimulai',
        ]);

        $response = $this->actingAs($user)->delete("/tasks/{$task->id}");
        $response->assertRedirect('/tasks');
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_can_update_task_status_quickly(): void
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Tugas Status Cepat',
            'priority' => 'sedang',
            'status' => 'belum dimulai',
        ]);

        $response = $this->actingAs($user)->patch("/tasks/{$task->id}/status", [
            'status' => 'selesai',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'selesai',
        ]);
    }
}
