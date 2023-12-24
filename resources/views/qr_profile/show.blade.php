@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Просмотр QR-профиля #'.$qrProfile->id])

    <div class="container-fluid py-4">
        @include('components.alert')
        <div class="card shadow-lg mx-4">
            <div class="card-body p-3">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative">
                            @if (!empty($photoPath))
                                <img src="{{ asset($photoPath) }}" alt="{{ $qrProfile->photo_file_name }}" class="w-100 border-radius-lg shadow-sm">
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
                                <span class="badge badge-sm bg-gradient-{{ $qrProfile->getStatusGradientColor(intval($qrProfile->id_status)) }}" data-bs-toggle="tooltip" data-bs-placement="right" title="Статус">{{ $qrProfile->statusName }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                        @if(!empty($qrProfile->userWhoCreated) && (int) $qrProfile->userWhoCreated->id > 0)
                            <div class="row">
                                <span class="text-left text-secondary">Создал: {{ $qrProfile->userWhoCreated->fullName }} ({{ $qrProfile->created_at->format('d.m.Y H:i:s') }})</span>
                            </div>
                        @endif
                        @if(!empty($qrProfile->userWhoUpdated) && (int) $qrProfile->userWhoUpdated->id > 0)
                            <div class="row">
                                <span class="text-left text-secondary">Редактировал: {{ $qrProfile->userWhoUpdated->fullName }} ({{ $qrProfile->updated_at->format('d.m.Y H:i:s') }})</span>
                            </div>
                        @endif
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
                                <div class="col-md-4"><span class="text-uppercase text-sm">Информация о QR-профиле</span></div>
                                <div class="col-md-8 d-flex align-items-center">
                                    <a href="{{ route('qrs.edit', $qrProfile->id) }}" class="btn btn-primary btn-sm ms-auto"><i class="fas fa-edit" style="margin-right: 5px;"></i>Редактировать QR-профиль</a>
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
                                        <input disabled="disabled" class="form-control" type="text" id="id_country" name="id_country" value="{{ $qrProfile->country->name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_city" class="form-control-label">Город</label>
                                        <input disabled="disabled" class="form-control" type="text" id="id_city" name="id_city" value="{{ $qrProfile->city->name }}">
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
                                            <a href="{{ asset($voiceMessagePath) }}" id="voice_message_file_name" target="_blank">{{ $qrProfile->voice_message_file_name }}</a>
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
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-md-12 align-items-center">
                                    <a href="{{ route('qrs.generate-qr-code-image', $qrProfile->id) }}" class="btn btn-primary btn-sm w-100" style="background-color: #5e72e4;"><i class="fas fa-qrcode" style="margin-right: 5px;"></i>Сгенерировать QR-код</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    @if (!empty($qrCodePath))
                                        <img src="{{ asset($qrCodePath) }}" alt="Qr code image" class="w-100 border-radius-lg shadow-sm">
                                    @endif
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
