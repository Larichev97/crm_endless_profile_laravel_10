@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.guest.navbar', ['title' => 'Просмотр QR-профиля #'.$qrProfile->id])

    <div class="container-fluid py-4" style="margin-top: 130px!important;">
        <div class="card shadow-lg">
            <div class="card-body p-3 d-flex flex-column align-items-center">
                @if (!empty($photoPath))
                    <img src="{{ asset($photoPath) }}" alt="{{ $qrProfile->photo_file_name }}" class="w-100 border-radius-lg shadow-sm mb-3">
                @else
                    <img src="/img/user_avatar.png" alt="photo_name" class="w-100 border-radius-lg shadow-sm mb-3">
                @endif
                <div class="text-center text-white">
                    <h5 class="mb-1" style="font-size: 1.5rem; font-weight: bold;">{{ $qrProfile->fullName }}</h5>
                    <h6 class="mb-3" style="font-size: 1.2rem;">({{ \Carbon\Carbon::parse($qrProfile->birth_date)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($qrProfile->death_date)->format('d.m.Y') }})</h6>
                </div>
            </div>
        </div>

        <div class="card shadow-lg mt-4">
            <div class="card-header text-center d-flex align-items-center justify-content-center" style="background-color: #172b4d; height: 100%">
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="text-white mb-0">Дополнительная информация</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @if(!empty($qrProfile->country->name))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="fw-bold">Страна</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <p class="text-dark">{{ $qrProfile->country->name }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($qrProfile->city->name))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="fw-bold">Город</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <p class="text-dark">{{ $qrProfile->city->name }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($qrProfile->last_wish))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="fw-bold">Последнее желание</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <p class="text-dark">{{ $qrProfile->last_wish }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($qrProfile->favourite_music_artist))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="fw-bold">Любимый музыкальный исполнитель</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <p class="text-dark">{{ $qrProfile->favourite_music_artist }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($qrProfile->profession))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="fw-bold">Профессия</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <p class="text-dark text-field">{{ $qrProfile->profession }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($qrProfile->hobbies))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="fw-bold">Хобби</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <p class="text-dark text-field">{{ $qrProfile->hobbies }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($qrProfile->voice_message_file_name) && !empty($voiceMessagePath))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="fw-bold">Голосовое сообщение</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <a class="text-dark text-field" href="{{ asset($voiceMessagePath) }}" id="voice_message_file_name" target="_blank">{{ $qrProfile->voice_message_file_name }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($qrProfile->link))
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="fw-bold">Ссылка</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <p class="text-dark text-field">{{ $qrProfile->link }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($qrProfile->biography))
                        <div class="col-md-12">
                            <div class="mb-3">
                                <h6 class="fw-bold">Биография</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <p class="text-dark text-field">{{ $qrProfile->biography }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @include('layouts.footers.auth.footer')
    </div>
@endsection
