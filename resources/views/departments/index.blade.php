@extends('layouts.app')

@section('title', 'Отделы')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Отделы</h2>
        <a href="{{ route('web.departments.create') }}" class="btn btn-dark">+ Добавить отдел</a>
    </div>

    <div class="row g-3">
        @forelse($departments as $department)
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title">{{ $department->name }}</h5>
                                <p class="text-muted small">{{ $department->description }}</p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('web.departments.edit', $department) }}" class="btn btn-sm btn-outline-secondary">Изменить</a>
                                <form action="{{ route('web.departments.destroy', $department) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Удалить отдел?')">Удалить</button>
                                </form>
                            </div>
                        </div>

                        @if($department->employees->count())
                            <hr>
                            <p class="small text-muted mb-1">Сотрудники ({{ $department->employees->count() }}):</p>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($department->employees as $employee)
                                    <span class="badge bg-secondary">
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col">
                <p class="text-muted">Отделов пока нет.</p>
            </div>
        @endforelse
    </div>
@endsection
