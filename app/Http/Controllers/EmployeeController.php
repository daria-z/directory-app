<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        $perPage = min($perPage, 100);

        return Employee::with('departments')->paginate($perPage);
    }

    /**
     * Store a newly created resource in storage.
     */
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
            'image_path' => 'nullable|string',
            'department_ids' => 'nullable|array',
            'department_ids.*' => 'exists:departments,id',
        ]);

        $employee = Employee::create($validated);

        if (! empty($validated['department_ids'])) {
            $employee->departments()->attach($validated['department_ids']);
        }

        return response()->json($employee->load('departments'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Employee::with('departments')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'birthday' => 'sometimes|date',
            'gender' => 'sometimes|in:male,female',
            'phone' => 'sometimes|string|unique:employees,phone,'.$employee->id,
            'email' => 'sometimes|email|unique:employees,email,'.$employee->id,
            'position' => 'sometimes|string|max:255',
            'image_path' => 'nullable|string',
            'department_ids' => 'nullable|array',
            'department_ids.*' => 'exists:departments,id',
        ]);

        $employee->update($validated);

        if (isset($validated['department_ids'])) {
            $employee->departments()->sync($validated['department_ids']);
        }

        return response()->json($employee->load('departments'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->json(null, 204);
    }
}
