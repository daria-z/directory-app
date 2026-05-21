<?php

namespace Tests\Unit;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_belongs_to_many_departments(): void
    {
        $employee = Employee::factory()->create();
        $departments = Department::factory()->count(2)->create();

        $employee->departments()->attach($departments->pluck('id'));

        $this->assertInstanceOf(Collection::class, $employee->departments);
        $this->assertCount(2, $employee->departments);
        $this->assertTrue($employee->departments->every(fn ($dep) => $dep instanceof Department));
    }

    public function test_employee_fillable_attributes(): void
    {
        $employee = new Employee();
        $fillable = $employee->getFillable();

        $this->assertContains('first_name', $fillable);
        $this->assertContains('last_name', $fillable);
        $this->assertContains('birthday', $fillable);
        $this->assertContains('gender', $fillable);
        $this->assertContains('phone', $fillable);
        $this->assertContains('email', $fillable);
        $this->assertContains('image_path', $fillable);
        $this->assertContains('position', $fillable);
    }
}
