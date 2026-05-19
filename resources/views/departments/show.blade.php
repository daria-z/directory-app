@extends('layouts.app')

@section('title', $department->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ $department->name }}</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('web.departments.edit', $department) }}" class="btn btn-outline-secondary">Изменить</a>
            <a href="{{ route('web.departments.index') }}" class="btn btn-outline-dark">Назад</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p class="text-muted mb-0">{{ $department->description ?? 'Описание не указано' }}</p>
        </div>
    </div>

    <h5>Сотрудники ({{ $department->employees->count() }})</h5>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                <tr>
                    <th>Имя</th>
                    <th>Должность</th>
                    <th>Email</th>
                </tr>
                </thead>
                <tbody>
                @forelse($department->employees as $employee)
                    <tr>
                        <td class="align-middle">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                        <td class="align-middle text-muted">{{ $employee->position }}</td>
                        <td class="align-middle text-muted">{{ $employee->email }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Сотрудников пока нет.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection