@section('content')
    @include('layouts.navbars.guest.navbar', ['title' => 'Перегляд QR-профілю #'.$qrProfile->id])

    <div class="container-md py-4" style="margin-top: 130px!important;">
        <div class="card shadow-lg">
            <div class="card-body p-6 d-flex flex-column align-items-center">
                @if (!empty($photoPath))
                    <img src="{{ asset($photoPath) }}" alt="{{ $qrProfile->photo_file_name }}" class="w-100 border-radius-lg shadow-sm mb-3">
                @else
                    <img src="/img/user_avatar.png" alt="user_avatar.png" class="w-100 border-radius-lg shadow-sm mb-3">
                @endif
                <div class="text-center text-white">
                    <h5 class="mb-1" style="font-size: 1.5rem; font-weight: bold;">{{ $qrProfile->fullName }}</h5>
                    <h5 class="mb-3">
                        @if(!empty($qrProfile->birth_date)) {{ \Carbon\Carbon::parse($qrProfile->birth_date)->format('d.m.Y') }} @else <span class="align-content-center" style="font-size: 17px!important;">∞</span> @endif
                        -
                        @if(!empty($qrProfile->death_date)) {{ \Carbon\Carbon::parse($qrProfile->death_date)->format('d.m.Y') }} @else <span class="align-content-center" style="font-size: 17px!important;">∞</span >@endif
                    </h5>
                </div>
            </div>
        </div>

        <div class="card shadow-lg mt-4">
            <div class="card-header text-center d-flex align-items-center justify-content-center" style="background-color: #172b4d;">
                <h5 class="text-white mb-0">Додаткова інформація</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if(!empty($qrProfile->country->name))
                        <div class="col-12">
                            <div class="mb-3">
                                <h6 class="fw-bold">Країна народження</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <span class="text-dark">{{ $qrProfile->country->name }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($qrProfile->city->name))
                        <div class="col-12">
                            <div class="mb-3">
                                <h6 class="fw-bold">Місто народження</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <span class="text-dark">{{ $qrProfile->city->name }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($qrProfile->last_wish))
                        <div class="col-12">
                            <div class="mb-3">
                                <h6 class="fw-bold">Останнє бажання</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <span class="text-dark">{{ $qrProfile->last_wish }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($qrProfile->favourite_music_artist))
                        <div class="col-12">
                            <div class="mb-3">
                                <h6 class="fw-bold">Улюблена музика</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <a href="https://music.youtube.com/search?q={{ $qrProfile->favourite_music_artist }}" class="text-dark" target="_blank" style="font-weight: bold!important;">{{ $qrProfile->favourite_music_artist }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($qrProfile->profession))
                        <div class="col-12">
                            <div class="mb-3">
                                <h6 class="fw-bold">Професія</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <span class="text-dark text-field">{{ $qrProfile->profession }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($qrProfile->hobbies))
                        <div class="col-12">
                            <div class="mb-3">
                                <h6 class="fw-bold">Хобі</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <span class="text-dark text-field">{{ $qrProfile->hobbies }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($qrProfile->voice_message_file_name) && !empty($voiceMessagePath))
                        <div class="col-12">
                            <div class="mb-3">
                                <h6 class="fw-bold">Голосове повідомлення</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <a class="text-dark text-field" href="{{ asset($voiceMessagePath) }}" id="voice_message_file_name" target="_blank">{{ $qrProfile->voice_message_file_name }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($qrProfile->link))
                        <div class="col-12">
                            <div class="mb-3">
                                <h6 class="fw-bold">Посилання</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <a href="{{ $qrProfile->link }}" class="text-dark text-field" style="font-weight: bold!important;" target="_blank">{{ $qrProfile->link }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($qrProfile->biography))
                        <div class="col-12">
                            <div class="mb-3">
                                <h6 class="fw-bold">Біографія</h6>
                                <div class="mb-4 p-3 border bg-light" style="border-radius: 1rem!important;">
                                    <span class="text-dark text-field">{{ $qrProfile->biography }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if(!empty($sliderGalleryImagesData) && is_array($sliderGalleryImagesData))
            <div class="card shadow-lg mt-4">
                <div class="card-header text-center d-flex align-items-center justify-content-center" style="background-color: #172b4d;">
                    <h5 class="text-white mb-0">Галерея</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-4 p-5" style="border-radius: 1rem!important;">
                                @include('components.slider', ['galleryData' => $sliderGalleryImagesData, 'idGallery' => 'qrProfileGallery', 'imageHeight' => 500])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @include('layouts.footers.guest.footer')
    </div>
@endsection
