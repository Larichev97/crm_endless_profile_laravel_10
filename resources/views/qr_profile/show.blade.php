@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Просмотр QR-профиля #'.$qrProfile->id])

    <div class="container-fluid py-4 mt-4">
        <div class="card shadow-lg mx-4">
            <div class="card-body p-3">
                <div>
                    @include('components.alert')
                </div>
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative">
                            @if (!empty($photoPath))
                                <img src="{{ asset($photoPath) }}" alt="photo_name" class="w-100 border-radius-lg shadow-sm">
                            @else
                                <img src="/img/user_avatar.png" alt="photo_name" class="w-100 border-radius-lg shadow-sm">
                            @endif
                        </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">
                                {{ $qrProfile->fullName }}
                            </h5>
                            <p class="mb-0 font-weight-bold text-sm">
                                <span class="badge badge-sm bg-gradient-success">{{ $qrProfile->statusName }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                        <div class="nav-wrapper position-relative end-0">
                            <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center "
                                        data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">
                                        <i class="ni ni-app"></i>
                                        <span class="ms-2">Приложение</span>
                                    </a>
                                </li>
                            </ul>
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
                                <div class="col-md-6"><span class="text-uppercase text-sm">Информация о QR-профиле</span></div>
                                <div class="col-md-6 d-flex align-items-center">
                                    <a href="{{ route('qrs.edit', $qrProfile->id) }}" class="btn btn-primary btn-sm ms-auto">Редактировать</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="birth_date" class="form-control-label">Дата рождения</label>
                                        <input class="form-control" disabled="disabled" type="date" id="birth_date" name="birth_date" value="{{ $qrProfile->birth_date }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="death_date" class="form-control-label">Дата смерти</label>
                                        <input class="form-control" disabled="disabled" type="date" id="death_date" name="death_date" value="{{ $qrProfile->death_date }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_country" class="form-control-label">Страна</label>
                                        <input disabled="disabled" class="form-control" type="text" id="id_country" name="id_country" value="{{ $qrProfile->id_country }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_city" class="form-control-label">Город</label>
                                        <input disabled="disabled" class="form-control" type="text" id="id_city" name="id_city" value="{{ $qrProfile->id_city }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="profession" class="form-control-label">Профессия</label>
                                        <input disabled="disabled" class="form-control" type="text" id="profession" name="profession" value="{{ $qrProfile->profession }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hobbies" class="form-control-label">Хобби</label>
                                        <input disabled="disabled" class="form-control" type="text" id="hobbies" name="hobbies" value="{{ $qrProfile->hobbies }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_wish" class="form-control-label">Последнее желание</label>
                                        <input disabled="disabled" class="form-control" type="text" id="last_wish" name="last_wish" value="{{ $qrProfile->last_wish }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="favourite_music_artist" class="form-control-label">Любимый музыкальный исполнитель</label>
                                        <input disabled="disabled" class="form-control" type="text" id="favourite_music_artist" name="favourite_music_artist" value="{{ $qrProfile->favourite_music_artist }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="geo_latitude" class="form-control-label">Геолокация (широта)</label>
                                        <input disabled="disabled" class="form-control" type="text" id="geo_latitude" name="geo_latitude" value="{{ $qrProfile->geo_latitude }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="geo_longitude" class="form-control-label">Геолокация (долгота)</label>
                                        <input disabled="disabled" class="form-control" type="text" id="geo_longitude" name="geo_longitude" value="{{ $qrProfile->geo_longitude }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="link" class="form-control-label">Ссылка</label>
                                        <br/>
                                        @if(!empty($qrProfile->link))
                                            <a href="{{ $qrProfile->link }}" id="link"  target="_blank">{{ $qrProfile->link }}</a>
                                        @else
                                            Не указана.
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="voice_message_file_name" class="form-control-label">Текущее голосовое сообщение</label>
                                        <br/>
                                        @if (!empty($voiceMessagePath))
                                            <a href="{{ asset($voiceMessagePath) }}" id="voice_message_file_name" target="_blank">{{ $voiceMessagePath }}</a>
                                        @else
                                            <span id="voice_message_file_name">Не загружено.</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="biography" class="form-control-label">Биография</label>
                                        <textarea disabled="disabled" class="form-control" type="text" rows="5" id="biography" name="biography">{{ $qrProfile->biography }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-profile">
                        <img src="/img/bg-profile.jpg" alt="Image placeholder" class="card-img-top">
                        <div class="row justify-content-center">
                            <div class="col-4 col-lg-4 order-lg-2">
                                <div class="mt-n4 mt-lg-n6 mb-4 mb-lg-0">
                                    <a href="javascript:;">
                                        <img src="/img/team-2.jpg"
                                            class="rounded-circle img-fluid border border-2 border-white">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">
                            <div class="d-flex justify-content-between">
                                <a href="javascript:;" class="btn btn-sm btn-info mb-0 d-none d-lg-block">Connect</a>
                                <a href="javascript:;" class="btn btn-sm btn-info mb-0 d-block d-lg-none"><i
                                        class="ni ni-collection"></i></a>
                                <a href="javascript:;"
                                    class="btn btn-sm btn-dark float-right mb-0 d-none d-lg-block">Message</a>
                                <a href="javascript:;" class="btn btn-sm btn-dark float-right mb-0 d-block d-lg-none"><i
                                        class="ni ni-email-83"></i></a>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col">
                                    <div class="d-flex justify-content-center">
                                        <div class="d-grid text-center">
                                            <span class="text-lg font-weight-bolder">22</span>
                                            <span class="text-sm opacity-8">Friends</span>
                                        </div>
                                        <div class="d-grid text-center mx-4">
                                            <span class="text-lg font-weight-bolder">10</span>
                                            <span class="text-sm opacity-8">Photos</span>
                                        </div>
                                        <div class="d-grid text-center">
                                            <span class="text-lg font-weight-bolder">89</span>
                                            <span class="text-sm opacity-8">Comments</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <h5>
                                    Mark Davis<span class="font-weight-light">, 35</span>
                                </h5>
                                <div class="h6 font-weight-300">
                                    <i class="ni location_pin mr-2"></i>Bucharest, Romania
                                </div>
                                <div class="h6 mt-4">
                                    <i class="ni business_briefcase-24 mr-2"></i>Solution Manager - Creative Tim Officer
                                </div>
                                <div>
                                    <i class="ni education_hat mr-2"></i>University of Computer Science
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
