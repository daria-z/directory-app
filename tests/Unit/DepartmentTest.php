<?php

namespace Tests\Unit;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_department_belongs_to_many_employees(): void
    {
        $department = Department::factory()->create();
        $employees = Employee::factory()->count(2)->create();

        $department->employees()->attach($employees->pluck('id'));

        $this->assertInstanceOf(Collection::class, $department->employees);
        $this->assertCount(2, $department->employees);
        $this->assertTrue($department->employees->every(fn ($emp) => $emp instanceof Employee));
    }

    public function test_department_fillable_attributes(): void
    {
        $department = new Department();
        $fillable = $department->getFillable();

        $this->assertContains('name', $fillable);
        $this->assertContains('description', $fillable);
    }
}
