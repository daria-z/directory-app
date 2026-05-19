<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('departments')->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required|date',
            'gender' => 'required|in:male,female',
            'phone' => 'required|string|unique:employees',
            'email' => 'required|email|unique:employees',
            'position' => 'required|string|max:255',
            'department_ids' => 'nullable|array',
            'department_ids.*' => 'exists:departments,id',
        ]);

        $employee = Employee::create($validated);

        if (!empty($validated['department_ids'])) {
            $employee->departments()->attach($validated['department_ids']);
        }

        return redirect()->route('web.employees.index')
            ->with('success', 'Сотрудник создан');
    }

    public function show(Employee $employee)
    {
        $employee->load('departments');
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::all();
        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'birthday' => 'sometimes|date',
            'gender' => 'sometimes|in:male,female',
            'phone' => 'sometimes|string|unique:employees,phone,' . $employee->id,
            'email' => 'sometimes|email|unique:employees,email,' . $employee->id,
            'position' => 'sometimes|string|max:255',
            'department_ids' => 'nullable|array',
            'department_ids.*' => 'exists:departments,id',
        ]);

        $employee->update($validated);

        $employee->departments()->sync($validated['department_ids'] ?? []);

        return redirect()->route('web.employees.index')
            ->with('success', 'Сотрудник обновлён');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('web.employees.index')
            ->with('success', 'Сотрудник удалён');
    }
}
