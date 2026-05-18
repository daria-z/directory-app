@extends('layouts.app')

@section('title', 'Новый отдел')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Новый отдел</h4>
                        <a href="{{ route('web.departments.index') }}" class="btn btn-sm btn-outline-secondary">← Назад</a>
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

                    <form action="{{ route('web.departments.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Название</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Описание</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">Создать</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
