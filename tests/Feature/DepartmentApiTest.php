<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentApiTest extends TestCase
{
    use RefreshDatabase;

    // --- Публичные маршруты ---

    public function test_index_returns_paginated_departments(): void
    {
        Department::factory()->count(3)->create();

        $response = $this->getJson('/api/departments');

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [['id', 'name', 'description', 'employees']],
            'current_page',
            'total',
        ]);
    }

    public function test_show_returns_department_with_employees(): void
    {
        $department = Department::factory()->create();

        $response = $this->getJson("/api/departments/{$department->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $department->id, 'name' => $department->name]);
    }

    public function test_show_returns_404_for_nonexistent_department(): void
    {
        $this->getJson('/api/departments/999')->assertStatus(404);
    }

    // --- Защищённые маршруты: требуют авторизации ---

    public function test_store_requires_auth(): void
    {
        $this->postJson('/api/departments', ['name' => 'HR'])->assertStatus(401);
    }

    public function test_update_requires_auth(): void
    {
        $department = Department::factory()->create();

        $this->putJson("/api/departments/{$department->id}", ['name' => 'New'])->assertStatus(401);
    }

    public function test_destroy_requires_auth(): void
    {
        $department = Department::factory()->create();

        $this->deleteJson("/api/departments/{$department->id}")->assertStatus(401);
    }

    // --- CRUD ---

    public function test_store_creates_department(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/departments', [
            'name' => 'Разработка',
            'description' => 'Backend и Frontend команда',
        ]);

        $response->assertStatus(201)->assertJsonFragment(['name' => 'Разработка']);
        $this->assertDatabaseHas('departments', ['name' => 'Разработка']);
    }

    public function test_store_requires_name(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/api/departments', ['description' => 'Без названия'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_update_changes_department_name(): void
    {
        $user = User::factory()->create();
        $department = Department::factory()->create(['name' => 'Старое название']);

        $this->actingAs($user)
            ->putJson("/api/departments/{$department->id}", ['name' => 'Новое название'])
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'Новое название']);
    }

    public function test_update_allows_partial_patch(): void
    {
        $user = User::factory()->create();
        $department = Department::factory()->create(['name' => 'HR', 'description' => 'Старое описание']);

        $this->actingAs($user)
            ->patchJson("/api/departments/{$department->id}", ['description' => 'Новое описание'])
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'HR', 'description' => 'Новое описание']);
    }

    public function test_destroy_deletes_department(): void
    {
        $user = User::factory()->create();
        $department = Department::factory()->create();

        $this->actingAs($user)
            ->deleteJson("/api/departments/{$department->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('departments', ['id' => $department->id]);
    }

    public function test_update_rejects_empty_name(): void
    {
        $user = User::factory()->create();
        $department = Department::factory()->create(['name' => 'Original Name']);

        $this->actingAs($user)
            ->patchJson("/api/departments/{$department->id}", ['name' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }
}
