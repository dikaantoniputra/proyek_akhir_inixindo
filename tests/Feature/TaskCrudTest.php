<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_authenticated_user_can_view_create_task_page_with_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('tasks.create'));
        $response->assertStatus(200);
        $response->assertSee('Judul Tugas');
        $response->assertSee('Prioritas');
        $response->assertSee('Status Tugas');
        $response->assertSee('name="status"', false);
        $response->assertSee('name="priority"', false);
        $response->assertSee('name="attachment"', false);
        $response->assertSee('Belum Dimulai');
        $response->assertSee('Sedang Dikerjakan');
        $response->assertSee('Selesai');
    }

    public function test_user_can_create_a_task_with_attachment(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Proyek',
            'slug' => 'proyek',
        ]);

        $file = UploadedFile::fake()->create('dokumen_laporan.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)->post('/tasks', [
            'title' => 'Membangun fitur manajemen tugas',
            'description' => 'Implementasi CRUD dengan validasi dan upload file',
            'priority' => 'tinggi',
            'status' => 'belum dimulai',
            'category_id' => $category->id,
            'due_date' => now()->addDays(5)->format('Y-m-d'),
            'attachment' => $file,
        ]);

        $response->assertRedirect('/tasks');
        
        $task = Task::where('user_id', $user->id)->first();
        $this->assertNotNull($task);
        $this->assertEquals('Membangun fitur manajemen tugas', $task->title);
        $this->assertEquals('dokumen_laporan.pdf', $task->attachment_name);
        $this->assertNotNull($task->attachment_path);
        Storage::disk('public')->assertExists($task->attachment_path);
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

    public function test_user_can_view_own_task_detail_with_attachment(): void
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Detail Tugas Penting',
            'description' => 'Rincian deskripsi tugas lengkap',
            'priority' => 'tinggi',
            'status' => 'dikerjakan',
            'attachment_name' => 'panduan.pdf',
            'attachment_path' => 'attachments/panduan.pdf',
            'attachment_size' => 102400,
        ]);

        $response = $this->actingAs($user)->get("/tasks/{$task->id}");
        $response->assertStatus(200);
        $response->assertSee('Detail Tugas Penting');
        $response->assertSee('panduan.pdf');
        $response->assertSee('100 KB');
    }

    public function test_user_can_view_edit_task_page_with_fields(): void
    {
        $user = User::factory()->create();
        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Tugas Edit Test',
            'priority' => 'sedang',
            'status' => 'dikerjakan',
        ]);

        $response = $this->actingAs($user)->get(route('tasks.edit', $task));
        $response->assertStatus(200);
        $response->assertSee('Tugas Edit Test');
        $response->assertSee('name="status"', false);
        $response->assertSee('name="priority"', false);
        $response->assertSee('name="attachment"', false);
    }

    public function test_user_can_update_own_task_and_replace_attachment(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        
        $oldFile = UploadedFile::fake()->create('lama.pdf', 100);
        $path = $oldFile->store('attachments', 'public');

        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Judul Awal',
            'priority' => 'rendah',
            'status' => 'belum dimulai',
            'attachment_path' => $path,
            'attachment_name' => 'lama.pdf',
            'attachment_size' => 102400,
        ]);

        $newFile = UploadedFile::fake()->create('revisi_baru.docx', 200);

        $response = $this->actingAs($user)->put("/tasks/{$task->id}", [
            'title' => 'Judul Telah Diperbarui',
            'priority' => 'tinggi',
            'status' => 'dikerjakan',
            'attachment' => $newFile,
        ]);

        $response->assertRedirect("/tasks/{$task->id}");
        
        $task->refresh();
        $this->assertEquals('Judul Telah Diperbarui', $task->title);
        $this->assertEquals('revisi_baru.docx', $task->attachment_name);
        Storage::disk('public')->assertMissing($path);
        Storage::disk('public')->assertExists($task->attachment_path);
    }

    public function test_user_can_remove_attachment_from_task(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        
        $file = UploadedFile::fake()->create('dokumen.pdf', 100);
        $path = $file->store('attachments', 'public');

        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Tugas dengan attachment',
            'priority' => 'sedang',
            'status' => 'belum dimulai',
            'attachment_path' => $path,
            'attachment_name' => 'dokumen.pdf',
            'attachment_size' => 102400,
        ]);

        $response = $this->actingAs($user)->put("/tasks/{$task->id}", [
            'title' => 'Tugas dengan attachment',
            'priority' => 'sedang',
            'status' => 'belum dimulai',
            'remove_attachment' => 1,
        ]);

        $response->assertRedirect("/tasks/{$task->id}");
        $task->refresh();
        $this->assertNull($task->attachment_path);
        $this->assertNull($task->attachment_name);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_user_can_delete_own_task_and_attachment(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        
        $file = UploadedFile::fake()->create('hapus_me.pdf', 100);
        $path = $file->store('attachments', 'public');

        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Tugas Yang Akan Dihapus',
            'priority' => 'rendah',
            'status' => 'belum dimulai',
            'attachment_path' => $path,
            'attachment_name' => 'hapus_me.pdf',
            'attachment_size' => 102400,
        ]);

        $response = $this->actingAs($user)->delete("/tasks/{$task->id}");
        $response->assertRedirect('/tasks');
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
        Storage::disk('public')->assertMissing($path);
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
