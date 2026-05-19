@extends('layouts.app')

@section('title', $employee->first_name . ' ' . $employee->last_name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ $employee->first_name }} {{ $employee->last_name }}</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('web.employees.edit', $employee) }}" class="btn btn-outline-secondary">Изменить</a>
            <a href="{{ route('web.employees.index') }}" class="btn btn-outline-dark">Назад</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Должность</dt>
                <dd class="col-sm-9">{{ $employee->position }}</dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $employee->email }}</dd>

                <dt class="col-sm-3">Телефон</dt>
                <dd class="col-sm-9">{{ $employee->phone }}</dd>

                <dt class="col-sm-3">Дата рождения</dt>
                <dd class="col-sm-9">{{ $employee->birthday }}</dd>

                <dt class="col-sm-3">Пол</dt>
                <dd class="col-sm-9">{{ $employee->gender === 'male' ? 'Мужской' : 'Женский' }}</dd>

                <dt class="col-sm-3">Отделы</dt>
                <dd class="col-sm-9">
                    @forelse($employee->departments as $department)
                        <span class="badge bg-secondary">{{ $department->name }}</span>
                    @empty
                        <span class="text-muted">Не указаны</span>
                    @endforelse
                </dd>
            </dl>
        </div>
    </div>
@endsection