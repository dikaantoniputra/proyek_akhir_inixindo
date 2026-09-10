<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_category_list(): void
    {
        $user = User::factory()->create();
        Category::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('categories.index');
    }

    public function test_user_can_create_category(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('categories.store'), [
            'name' => 'Backend Development',
            'color' => 'primary',
            'description' => 'Tugas-tugas terkait backend',
        ]);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('categories', [
            'name' => 'Backend Development',
            'slug' => 'backend-development',
            'color' => 'primary',
        ]);
    }

    public function test_user_can_update_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user)->put(route('categories.update', $category), [
            'name' => 'New Name',
            'color' => 'danger',
            'description' => 'Updated description',
        ]);

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'New Name',
            'slug' => 'new-name',
            'color' => 'danger',
        ]);
    }

    public function test_user_can_delete_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($user)->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'category_id' => null,
        ]);
    }
}
