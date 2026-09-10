<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_view_another_users_task(): void
    {
        $userA = User::factory()->create(['name' => 'User A']);
        $userB = User::factory()->create(['name' => 'User B']);

        
        $taskB = Task::create([
            'user_id' => $userB->id,
            'title' => 'Tugas Rahasia User B',
            'priority' => 'tinggi',
            'status' => 'belum dimulai',
        ]);

        
        $response = $this->actingAs($userA)->get("/tasks/{$taskB->id}");

        
        $response->assertStatus(403);
    }

    public function test_user_cannot_edit_another_users_task(): void
    {
        $userA = User::factory()->create(['name' => 'User A']);
        $userB = User::factory()->create(['name' => 'User B']);

        $taskB = Task::create([
            'user_id' => $userB->id,
            'title' => 'Tugas Milik User B',
            'priority' => 'tinggi',
            'status' => 'belum dimulai',
        ]);

        
        $response = $this->actingAs($userA)->get("/tasks/{$taskB->id}/edit");
        $response->assertStatus(403);
    }

    public function test_user_cannot_update_another_users_task(): void
    {
        $userA = User::factory()->create(['name' => 'User A']);
        $userB = User::factory()->create(['name' => 'User B']);

        $taskB = Task::create([
            'user_id' => $userB->id,
            'title' => 'Tugas Asli User B',
            'priority' => 'tinggi',
            'status' => 'belum dimulai',
        ]);

        
        $response = $this->actingAs($userA)->put("/tasks/{$taskB->id}", [
            'title' => 'Diubah oleh User A',
            'priority' => 'rendah',
            'status' => 'selesai',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', [
            'id' => $taskB->id,
            'title' => 'Tugas Asli User B',
        ]);
    }

    public function test_user_cannot_delete_another_users_task(): void
    {
        $userA = User::factory()->create(['name' => 'User A']);
        $userB = User::factory()->create(['name' => 'User B']);

        $taskB = Task::create([
            'user_id' => $userB->id,
            'title' => 'Tugas User B Yang Tidak Boleh Dihapus User A',
            'priority' => 'tinggi',
            'status' => 'belum dimulai',
        ]);

        
        $response = $this->actingAs($userA)->delete("/tasks/{$taskB->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['id' => $taskB->id]);
    }

    public function test_user_cannot_update_status_of_another_users_task(): void
    {
        $userA = User::factory()->create(['name' => 'User A']);
        $userB = User::factory()->create(['name' => 'User B']);

        $taskB = Task::create([
            'user_id' => $userB->id,
            'title' => 'Tugas Status User B',
            'priority' => 'tinggi',
            'status' => 'belum dimulai',
        ]);

        
        $response = $this->actingAs($userA)->patch("/tasks/{$taskB->id}/status", [
            'status' => 'selesai',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', [
            'id' => $taskB->id,
            'status' => 'belum dimulai',
        ]);
    }

    public function test_task_list_only_contains_own_tasks(): void
    {
        $userA = User::factory()->create(['name' => 'User A']);
        $userB = User::factory()->create(['name' => 'User B']);

        $taskA = Task::create([
            'user_id' => $userA->id,
            'title' => 'Tugas Khusus User A',
            'priority' => 'tinggi',
            'status' => 'belum dimulai',
        ]);

        $taskB = Task::create([
            'user_id' => $userB->id,
            'title' => 'Tugas Khusus User B',
            'priority' => 'tinggi',
            'status' => 'belum dimulai',
        ]);

        $response = $this->actingAs($userA)->get('/tasks');
        $response->assertStatus(200);
        $response->assertSee('Tugas Khusus User A');
        $response->assertDontSee('Tugas Khusus User B');
    }
}
