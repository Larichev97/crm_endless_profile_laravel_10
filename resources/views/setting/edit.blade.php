@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Редактирование настройки'])

    <div class="container-fluid py-4 mt-5">
        <form class="card p-4" action="{{ route('admin.settings.update', $setting->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <a style="width: fit-content;" class="btn btn-dark" href="{{ route('admin.settings.index') }}">Назад</a>

            <div>
                @include('components.alert')
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Название настройки <span style="color: red">*</span></strong>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Укажите название настройки..." value="{{ old('name', $setting->name) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Значение настройки <span style="color: red">*</span></strong>
                        <input type="text" name="value" class="form-control @error('value') is-invalid @enderror" placeholder="Укажите значение настройки..." value="{{ old('value', $setting->value) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-success btn-lg w-100">Сохранить</button>
                </div>
            </div>

        </form>

        @include('layouts.footers.auth.footer')
    </div>
@endsection
