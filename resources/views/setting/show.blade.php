@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Просмотр настройки #'.$setting->id])

    <div class="container-fluid py-4">
        @include('components.alert')

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-md-4"><span class="text-uppercase text-sm">Информация о настройке</span></div>
                                <div class="col-md-8 d-flex align-items-center">
                                    <a href="{{ route('settings.edit', $setting->id) }}" class="btn btn-primary btn-sm ms-auto"><i class="fas fa-edit" style="margin-right: 5px;"></i>Редактировать настройку</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="value" class="form-control-label">Название настройки</label>
                                        <input class="form-control" disabled="disabled" type="text" id="value" name="value" value="{{ $setting->name }}">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="value" class="form-control-label">Значение настройки</label>
                                        <input class="form-control" disabled="disabled" type="text" id="value" name="value" value="{{ $setting->value }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('layouts.footers.auth.footer')
        </div>
    </div>
@endsection
