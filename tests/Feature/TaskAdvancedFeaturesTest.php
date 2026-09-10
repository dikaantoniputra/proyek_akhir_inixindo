<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\TaskChecklist;
use App\Models\TaskComment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAdvancedFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_kanban_board(): void
    {
        $user = User::factory()->create();
        $task1 = Task::factory()->create(['user_id' => $user->id, 'status' => 'belum dimulai']);
        $task2 = Task::factory()->create(['user_id' => $user->id, 'status' => 'dikerjakan']);
        $task3 = Task::factory()->create(['user_id' => $user->id, 'status' => 'selesai']);

        $response = $this->actingAs($user)->get(route('tasks.kanban'));

        $response->assertStatus(200);
        $response->assertSee('Papan Kanban Tugas');
        $response->assertSee($task1->title);
        $response->assertSee($task2->title);
        $response->assertSee($task3->title);
    }

    public function test_user_can_add_checklist_item_to_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('tasks.checklists.store', $task), [
            'title' => 'Menyusun wireframe awal',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('task_checklists', [
            'task_id' => $task->id,
            'title' => 'Menyusun wireframe awal',
            'is_completed' => false,
        ]);
    }

    public function test_user_can_toggle_checklist_status(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $checklist = TaskChecklist::create([
            'task_id' => $task->id,
            'title' => 'Selesaikan modul testing',
            'is_completed' => false,
        ]);

        $response = $this->actingAs($user)->patch(route('tasks.checklists.toggle', $checklist));

        $response->assertRedirect();
        $this->assertTrue($checklist->fresh()->is_completed);

        
        $this->actingAs($user)->patch(route('tasks.checklists.toggle', $checklist));
        $this->assertFalse($checklist->fresh()->is_completed);
    }

    public function test_user_can_delete_checklist_item(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $checklist = TaskChecklist::create([
            'task_id' => $task->id,
            'title' => 'Item checklist yang akan dihapus',
            'is_completed' => false,
        ]);

        $response = $this->actingAs($user)->delete(route('tasks.checklists.destroy', $checklist));

        $response->assertRedirect();
        $this->assertDatabaseMissing('task_checklists', ['id' => $checklist->id]);
    }

    public function test_user_cannot_manipulate_another_users_checklist(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $taskA = Task::factory()->create(['user_id' => $userA->id]);
        $checklistA = TaskChecklist::create([
            'task_id' => $taskA->id,
            'title' => 'Checklist Rahasia User A',
            'is_completed' => false,
        ]);

        
        $response = $this->actingAs($userB)->post(route('tasks.checklists.store', $taskA), [
            'title' => 'Hacker checklist',
        ]);
        $response->assertStatus(403);

        
        $response = $this->actingAs($userB)->patch(route('tasks.checklists.toggle', $checklistA));
        $response->assertStatus(403);

        
        $response = $this->actingAs($userB)->delete(route('tasks.checklists.destroy', $checklistA));
        $response->assertStatus(403);
    }

    public function test_user_can_add_comment_to_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('tasks.comments.store', $task), [
            'comment' => 'Progres hari ini: API endpoint berhasil dibuat dan diuji.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('task_comments', [
            'task_id' => $task->id,
            'user_id' => $user->id,
            'comment' => 'Progres hari ini: API endpoint berhasil dibuat dan diuji.',
        ]);
    }

    public function test_user_can_delete_own_comment(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $comment = TaskComment::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'comment' => 'Catatan yang salah ketik',
        ]);

        $response = $this->actingAs($user)->delete(route('tasks.comments.destroy', $comment));

        $response->assertRedirect();
        $this->assertDatabaseMissing('task_comments', ['id' => $comment->id]);
    }

    public function test_checklist_progress_percentage_calculation(): void
    {
        $task = Task::factory()->create(['status' => 'belum dimulai']);
        
        $this->assertEquals(0, $task->checklist_progress_percentage);

        $cl1 = TaskChecklist::create(['task_id' => $task->id, 'title' => 'Step 1', 'is_completed' => true]);
        $cl2 = TaskChecklist::create(['task_id' => $task->id, 'title' => 'Step 2', 'is_completed' => false]);
        $cl3 = TaskChecklist::create(['task_id' => $task->id, 'title' => 'Step 3', 'is_completed' => false]);
        $cl4 = TaskChecklist::create(['task_id' => $task->id, 'title' => 'Step 4', 'is_completed' => false]);

        $this->assertEquals(25, $task->fresh()->checklist_progress_percentage);

        $cl2->update(['is_completed' => true]);
        $this->assertEquals(50, $task->fresh()->checklist_progress_percentage);

        $cl3->update(['is_completed' => true]);
        $cl4->update(['is_completed' => true]);
        $this->assertEquals(100, $task->fresh()->checklist_progress_percentage);
    }
}
