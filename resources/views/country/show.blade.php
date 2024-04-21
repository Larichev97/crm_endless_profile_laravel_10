@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Просмотр страны #'.$country->id])

    <div class="container-fluid py-4">
        @include('components.alert')
        <div class="card shadow-lg mx-4">
            <div class="card-body p-3">
                <div class="row gx-4">
                    <div class="col-auto">
                        @if (!empty($flagFilePath))
                            <div class="avatar avatar-xl position-relative">
                                <img src="{{ asset($flagFilePath) }}" alt="photo_name" class="w-100 border-radius-lg shadow-sm">
                            </div>
                        @else
                            Не загружен.
                        @endif
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">
                                {{ $country->name }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-md-4"><span class="text-uppercase text-sm">Информация о стране</span></div>
                                <div class="col-md-8 d-flex align-items-center">
                                    <a href="{{ route('admin.countries.edit', $country->id) }}" class="btn btn-primary btn-sm ms-auto"><i class="fas fa-edit" style="margin-right: 5px;"></i>Редактировать страну</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="iso_code" class="form-control-label">ISO код страны</label>
                                        <input class="form-control" disabled="disabled" type="text" id="iso_code" name="iso_code" value="{{ $country->iso_code }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_active" class="form-control-label">Включена</label>

                                        <div class="form-check">
                                            <input disabled class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" name="is_active" @if((int) (old('is_active', $country->is_active)) == 1) checked="" @endif value="1" id="is_active">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-profile">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-md-12 align-items-center">

                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

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
