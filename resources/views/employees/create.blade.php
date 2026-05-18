@extends('layouts.app')

@section('title', 'Новый сотрудник')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Новый сотрудник</h4>
                        <a href="{{ route('web.employees.index') }}" class="btn btn-sm btn-outline-secondary">← Назад</a>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('web.employees.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Имя</label>
                                <input type="text" name="first_name" class="form-control"
                                       value="{{ old('first_name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Фамилия</label>
                                <input type="text" name="last_name" class="form-control"
                                       value="{{ old('last_name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Дата рождения</label>
                                <input type="date" name="birthday" class="form-control"
                                       value="{{ old('birthday') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Пол</label>
                                <select name="gender" class="form-select" required>
                                    <option value="">— выберите —</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Мужской</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Женский</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Телефон</label>
                                <input type="text" name="phone" class="form-control"
                                       value="{{ old('phone') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                       value="{{ old('email') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Должность</label>
                                <input type="text" name="position" class="form-control"
                                       value="{{ old('position') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Отделы</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($departments as $department)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="department_ids[]"
                                                   value="{{ $department->id }}"
                                                   id="dept_{{ $department->id }}"
                                                {{ in_array($department->id, old('department_ids', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="dept_{{ $department->id }}">
                                                {{ $department->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-dark w-100">Создать</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
