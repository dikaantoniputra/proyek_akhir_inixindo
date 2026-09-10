<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_profile_page()
    {
        $response = $this->get(route('profile.edit'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_profile_page()
    {
        $user = User::factory()->create([
            'name' => 'Ahmad Dani',
            'email' => 'ahmad@example.com',
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Profil Pengguna');
        $response->assertSee('Ahmad Dani');
        $response->assertSee('ahmad@example.com');
    }

    public function test_user_can_update_profile_information()
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'email' => 'lama@example.com',
        ]);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Nama Baru',
            'email' => 'baru@example.com',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('Nama Baru', $user->name);
        $this->assertEquals('baru@example.com', $user->email);
    }

    public function test_user_cannot_use_email_already_taken_by_another_user()
    {
        User::factory()->create(['email' => 'existing@example.com']);
        $user = User::factory()->create(['email' => 'myemail@example.com']);

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'User Baru',
            'email' => 'existing@example.com',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_user_can_change_password_with_correct_current_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.password'), [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword456',
            'password_confirmation' => 'newpassword456',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword456', $user->password));
    }

    public function test_user_cannot_change_password_with_wrong_current_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('correctpassword'),
        ]);

        $response = $this->actingAs($user)->put(route('profile.password'), [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors(['current_password']);
    }

    public function test_user_can_export_tasks_to_csv()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Pekerjaan', 'slug' => 'pekerjaan', 'color' => 'primary']);

        Task::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Tugas Uji Ekspor',
            'description' => 'Deskripsi Uji',
            'priority' => 'tinggi',
            'status' => 'belum dimulai',
            'due_date' => now()->addDays(2),
        ]);

        $response = $this->actingAs($user)->get(route('tasks.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Tugas Uji Ekspor', $response->streamedContent());
    }

    public function test_admin_can_export_all_tasks_and_users_summary()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        Task::create([
            'user_id' => $user->id,
            'title' => 'Tugas Pegawai 1',
            'priority' => 'sedang',
            'status' => 'dikerjakan',
            'due_date' => now()->addDays(3),
        ]);

        
        $responseAll = $this->actingAs($admin)->get(route('admin.tasks.export'));
        $responseAll->assertStatus(200);
        $responseAll->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Tugas Pegawai 1', $responseAll->streamedContent());

        
        $responseSummary = $this->actingAs($admin)->get(route('admin.users.export'));
        $responseSummary->assertStatus(200);
        $responseSummary->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Tingkat Penyelesaian (%)', $responseSummary->streamedContent());
    }
}
