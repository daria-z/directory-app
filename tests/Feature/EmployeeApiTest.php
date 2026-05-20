<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeApiTest extends TestCase
{
    use RefreshDatabase;

    private function employeeData(array $override = []): array
    {
        return array_merge([
            'first_name' => 'Иван',
            'last_name'  => 'Иванов',
            'birthday'   => '1990-01-15',
            'gender'     => 'male',
            'phone'      => '+7 900 000-00-01',
            'email'      => 'ivan@example.com',
            'position'   => 'Developer',
        ], $override);
    }

    // --- Публичные маршруты ---

    public function test_index_returns_paginated_employees(): void
    {
        Employee::factory()->count(3)->create();

        $response = $this->getJson('/api/employees');

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [['id', 'first_name', 'last_name', 'departments']],
            'current_page',
            'total',
        ]);
    }

    public function test_show_returns_employee_with_departments(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->getJson("/api/employees/{$employee->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $employee->id]);
    }

    public function test_show_returns_404_for_nonexistent_employee(): void
    {
        $this->getJson('/api/employees/999')->assertStatus(404);
    }

    // --- Защищённые маршруты: требуют авторизации ---

    public function test_store_requires_auth(): void
    {
        $this->postJson('/api/employees', $this->employeeData())->assertStatus(401);
    }

    public function test_update_requires_auth(): void
    {
        $employee = Employee::factory()->create();

        $this->putJson("/api/employees/{$employee->id}", ['first_name' => 'Пётр'])->assertStatus(401);
    }

    public function test_destroy_requires_auth(): void
    {
        $employee = Employee::factory()->create();

        $this->deleteJson("/api/employees/{$employee->id}")->assertStatus(401);
    }

    // --- CRUD ---

    public function test_store_creates_employee(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/employees', $this->employeeData());

        $response->assertStatus(201)->assertJsonFragment(['first_name' => 'Иван']);
        $this->assertDatabaseHas('employees', ['email' => 'ivan@example.com']);
    }

    public function test_store_attaches_departments(): void
    {
        $user = User::factory()->create();
        $departments = Department::factory()->count(2)->create();

        $data = $this->employeeData(['department_ids' => $departments->pluck('id')->toArray()]);

        $response = $this->actingAs($user)->postJson('/api/employees', $data);

        $response->assertStatus(201);
        $employee = Employee::where('email', 'ivan@example.com')->first();
        $this->assertCount(2, $employee->departments);
    }

    public function test_store_validates_unique_email(): void
    {
        $user = User::factory()->create();
        Employee::factory()->create(['email' => 'ivan@example.com']);

        $this->actingAs($user)
            ->postJson('/api/employees', $this->employeeData())
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_store_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/employees', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['first_name', 'last_name', 'birthday', 'gender', 'phone', 'email', 'position']);
    }

    public function test_update_changes_employee_data(): void
    {
        $user = User::factory()->create();
        $employee = Employee::factory()->create();

        $this->actingAs($user)
            ->patchJson("/api/employees/{$employee->id}", ['position' => 'Senior Developer'])
            ->assertStatus(200)
            ->assertJsonFragment(['position' => 'Senior Developer']);
    }

    public function test_update_syncs_departments(): void
    {
        $user = User::factory()->create();
        $employee = Employee::factory()->create();
        $departments = Department::factory()->count(3)->create();

        $employee->departments()->attach($departments->take(2)->pluck('id'));

        $this->actingAs($user)->patchJson("/api/employees/{$employee->id}", [
            'department_ids' => [$departments->last()->id],
        ])->assertStatus(200);

        $this->assertCount(1, $employee->fresh()->departments);
        $this->assertEquals($departments->last()->id, $employee->fresh()->departments->first()->id);
    }

    public function test_destroy_deletes_employee(): void
    {
        $user = User::factory()->create();
        $employee = Employee::factory()->create();

        $this->actingAs($user)
            ->deleteJson("/api/employees/{$employee->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }
}
