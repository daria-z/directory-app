@extends('layouts.app')

@section('title', 'Сотрудники')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Сотрудники</h2>
        <a href="{{ route('web.employees.create') }}" class="btn btn-dark">+ Добавить сотрудника</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                <tr>
                    <th>Имя</th>
                    <th>Должность</th>
                    <th>Email</th>
                    <th>Отделы</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($employees as $employee)
                    <tr>
                        <td class="align-middle">
                            {{ $employee->first_name }} {{ $employee->last_name }}
                        </td>
                        <td class="align-middle text-muted">{{ $employee->position }}</td>
                        <td class="align-middle text-muted">{{ $employee->email }}</td>
                        <td class="align-middle">
                            @foreach($employee->departments as $department)
                                <span class="badge bg-secondary">{{ $department->name }}</span>
                            @endforeach
                        </td>
                        <td class="align-middle text-end">
                            <a href="{{ route('web.employees.edit', $employee) }}"
                               class="btn btn-sm btn-outline-secondary">Изменить</a>
                            <form action="{{ route('web.employees.destroy', $employee) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Удалить сотрудника?')">X</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Сотрудников пока нет.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
