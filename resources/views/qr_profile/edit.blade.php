@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Редактирование клиента'])

    <div class="container-fluid py-4 mt-4">
        <form class="card p-4" action="{{ route('qrs.update', $qrProfile->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <a style="width: fit-content;" class="btn btn-primary" href="{{ route('clients.index') }}"> Назад</a>

            <div>
                @include('components.alert')
            </div>

            <div class="row">
                <input type="hidden" name="photo_name" class="form-control" value="{{ $qrProfile->photo_name }}">
                <input type="hidden" name="voice_message_file_name" class="form-control" value="{{ $qrProfile->voice_message_file_name }}">
                <input type="hidden" name="id_user_add" class="form-control" value="{{ $qrProfile->id_user_add }}">
                <input type="hidden" name="id_user_update" class="form-control" value="{{ auth()->user()->id }}">

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Привязка к клиенту <span style="color: red">*</span></strong>
                        <br>
                        <select class="form-control" name="id_client" id="id_client" class="@error('id_client') is-invalid @enderror">
                            <option value="0" {{ $qrProfile->id_client == '0' ? 'selected' : '' }}>Выберите клиента из списка...</option>
                            {{-- Массив коллекций клиентов только с полями "id" и "name" --}}
                            @if(!empty($clientsListData))
                                @foreach($clientsListData as $clientItem)
                                    <option value="{{ $clientItem->id }}" {{ $qrProfile->id_client == $clientItem->id ? 'selected' : '' }}>
                                        {{ $clientItem->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Текущее фото QR-профиля</strong>
                        <br/>
                        <div class="avatar avatar-xxl position-relative">
                            @if (!empty($photoPath))
                                <img src="{{ asset($photoPath) }}" alt="{{ $qrProfile->photo_name }}" class="w-100 border-radius-lg shadow-sm">
                            @else
                                <img src="/img/user_avatar.png" alt="qr profile photo" class="w-100 border-radius-lg shadow-sm">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <input type="file" name="photo_file" id="input_photo_file" class="form-control @error('photo_file') is-invalid @enderror">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Статус <span style="color: red">*</span></strong>
                        <br>
                        @if(!empty($statusesListData))
                            <select class="form-control @error('id_status') is-invalid @enderror" name="id_status" id="id_status">
                                <option value="0" {{ old('id_status', $qrProfile->id_status) == '0' ? 'selected' : '' }}>Выберите из списка...</option>
                                @foreach($statusesListData as $statusItem)
                                    <option value="{{ $statusItem['id'] }}" {{ old('id_status', $qrProfile->id_status) == $statusItem['id'] ? 'selected' : '' }}>{{ $statusItem['name'] }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Имя <span style="color: red">*</span></strong>
                        <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" placeholder="Укажите имя..." value="{{ old('firstname', $qrProfile->firstname) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Фамилия <span style="color: red">*</span></strong>
                        <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" placeholder="Укажите фамилию..." value="{{ old('lastname', $qrProfile->lastname) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Отчество</strong>
                        <input type="text" name="surname" class="form-control @error('surname') is-invalid @enderror" placeholder="Укажите отчество..." value="{{ old('surname', $qrProfile->surname) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Дата рождения</strong>
                        <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" placeholder="Укажите дату рождения..." value="{{ old('birth_date', $qrProfile->birth_date) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Дата смерти <span style="color: red">*</span></strong>
                        <input type="date" name="death_date" class="form-control @error('death_date') is-invalid @enderror" placeholder="Укажите дату смерти..." value="{{ old('death_date', $qrProfile->death_date) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Страна <span style="color: red">*</span></strong>
                        <br>
                        <select class="form-control @error('id_country') is-invalid @enderror" name="id_country" id="id_country">
                            <option value="0" {{ old('id_country', $qrProfile->id_country) == '0' ? 'selected' : '' }}>Выберите из списка...</option>
                            {{-- Массив коллекций стран только с полями "id" и "name" --}}
                            @if(!empty($countriesListData))
                                @foreach($countriesListData as $countryItem)
                                    <option value="{{ $countryItem->id }}" {{ old('id_country', $qrProfile->id_country) == $countryItem->id ? 'selected' : '' }}>
                                        {{ $countryItem->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Город <span style="color: red">*</span></strong>
                        <br>
                        <select class="form-control @error('id_city') is-invalid @enderror" name="id_city" id="id_city">
                            <option value="1" {{ old('id_city', $qrProfile->id_city) == 1 ? 'selected' : '' }}>Днепр</option>
                            <option value="2" {{ old('id_city', $qrProfile->id_city) == 2 ? 'selected' : '' }}>Киев</option>
                            <option value="3" {{ old('id_city', $qrProfile->id_city) == 3 ? 'selected' : '' }}>Харьков</option>
                            <option value="4" {{ old('id_city', $qrProfile->id_city) == 4 ? 'selected' : '' }}>Запорожье</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Причина смерти</strong>
                        <input type="text" name="cause_death" class="form-control @error('cause_death') is-invalid @enderror" placeholder="Укажите причину смерти..." value="{{ old('cause_death', $qrProfile->cause_death) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Профессия</strong>
                        <input type="text" name="profession" class="form-control @error('profession') is-invalid @enderror" placeholder="Укажите профессию..." value="{{ old('profession', $qrProfile->profession) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Хобби</strong>
                        <input type="text" name="hobbies" class="form-control @error('hobbies') is-invalid @enderror" placeholder="Укажите хобби..." value="{{ old('hobbies', $qrProfile->hobbies) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Биография</strong>
                        <textarea class="form-control @error('biography') is-invalid @enderror" id="biography" name="biography" rows="4" placeholder="Укажите биографию...">{{ old('biography', $qrProfile->biography) }}</textarea>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Последнее желание</strong>
                        <input type="text" name="last_wish" class="form-control @error('last_wish') is-invalid @enderror" placeholder="Укажите последнее желание..." value="{{ old('last_wish', $qrProfile->last_wish) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Любимый музыкальный исполнитель</strong>
                        <input type="text" name="favourite_music_artist" class="form-control @error('favourite_music_artist') is-invalid @enderror" placeholder="Укажите исполнителя..." value="{{ old('favourite_music_artist', $qrProfile->favourite_music_artist) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Ссылка</strong>
                        <input type="text" name="link" class="form-control @error('link') is-invalid @enderror" placeholder="Укажите ссылку..." value="{{ old('link', $qrProfile->link) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Геолокация (широта)</strong>
                        <input type="text" name="geo_latitude" class="form-control @error('geo_latitude') is-invalid @enderror" placeholder="Укажите широту..." value="{{ old('geo_latitude', $qrProfile->geo_latitude) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Геолокация (долгота)</strong>
                        <input type="text" name="geo_longitude" class="form-control @error('geo_longitude') is-invalid @enderror" placeholder="Укажите долготу..." value="{{ old('geo_longitude', $qrProfile->geo_longitude) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Текущее голосовое сообщение</strong>
                        <br/>
                        <div>
                            @if (!empty($voiceMessagePath))
                                <a href="{{ asset($voiceMessagePath) }}" class="w-100 border-radius-lg shadow-sm">{{ $qrProfile->voice_message_file_name }}</a>
                            @else
                                Не загружено.
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <input type="file" name="voice_message_file" id="input_voice_message_file" class="form-control @error('voice_message_file') is-invalid @enderror">
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
