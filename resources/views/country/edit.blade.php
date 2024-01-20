@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Редактирование страны'])

    <div class="container-fluid py-4 mt-5">
        <form class="card p-4" action="{{ route('countries.update', $country->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <a style="width: fit-content;" class="btn btn-dark" href="{{ route('countries.index') }}">Назад</a>

            <div>
                @include('components.alert')
            </div>

            <div class="row">
                <input type="hidden" name="flag_file_name" class="form-control" value="{{ $country->flag_file_name }}">

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Текущее изображение флага</strong>
                        <br/>
                        @if (!empty($flagFilePath) && !empty($country->flag_file_name))
                            <div class="avatar avatar-xxl position-relative">
                                <img src="{{ asset($flagFilePath) }}" alt="{{ $country->flag_file_name }}" class="w-100 border-radius-lg shadow-sm">
                            </div>
                        @else
                            <div class="">Не загружено.</div>
                        @endif
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <input type="file" name="flag_file" id="input_flag_file" class="form-control @error('flag_file') is-invalid @enderror">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Название <span style="color: red">*</span></strong>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Укажите название..." value="{{ old('name', $country->name) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>ISO код страны <span style="color: red">*</span></strong>
                        <input type="text" name="iso_code" class="form-control @error('iso_code') is-invalid @enderror" placeholder="Укажите ISO код страны..." value="{{ old('iso_code', $country->iso_code) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Включена</strong>
                        <div class="form-check">
                            <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" name="is_active" @if((int) (old('is_active', $country->is_active)) == 1) checked="" @endif value="1" id="is_active">
                        </div>
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
