@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Редактирование клиента'])

    <div class="container-fluid py-4 mt-5">
        <form class="card p-4" action="{{ route('clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <a style="width: fit-content;" class="btn btn-dark" href="{{ route('clients.index') }}">Назад</a>

            <div>
                @include('components.alert')
            </div>

            <div class="row">
                <input type="hidden" name="photo_name" class="form-control" value="{{ $client->photo_name }}">
                <input type="hidden" name="id_client" class="form-control" value="{{ $client->id }}">
                <input type="hidden" name="id_user_add" class="form-control" value="{{ $client->id_user_add }}">
                <input type="hidden" name="id_user_update" class="form-control" value="{{ auth()->user()->id }}">

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Текущее фото клиента</strong>
                        <br/>
                        <div class="avatar avatar-xxl position-relative">
                            @if (!empty($clientPhotoPath))
                                <img src="{{ asset($clientPhotoPath) }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                            @else
                                <img src="/img/user_avatar.png" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <input type="file" name="image" id="inputImage" class="form-control @error('image') is-invalid @enderror">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Статус <span style="color: red">*</span></strong>
                        <br>
                        @if(!empty($statusesListData))
                            <select class="form-control @error('id_status') is-invalid @enderror" name="id_status" id="id_status">
                                @foreach($statusesListData as $statusItem)
                                    <option value="{{ $statusItem['id'] }}" {{ old('id_status', $client->id_status) == $statusItem['id'] ? 'selected' : '' }}>{{ $statusItem['name'] }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Имя <span style="color: red">*</span></strong>
                        <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" placeholder="Имя" value="{{ old('firstname', $client->firstname) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Фамилия <span style="color: red">*</span></strong>
                        <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" placeholder="Укажите фамилию клиента..." value="{{ old('lastname', $client->lastname) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Отчество</strong>
                        <input type="text" name="surname" class="form-control @error('surname') is-invalid @enderror" placeholder="Укажите отчество клиента..." value="{{ old('surname', $client->surname) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Email <span style="color: red">*</span></strong>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Укажите email клиента..." value="{{ old('email', $client->email) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Моб. телефон <span style="color: red">*</span></strong>
                        <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" placeholder="+380680000000" value="{{ old('phone_number', $client->phone_number) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Дата рождения</strong>
                        <input type="date" name="bdate" class="form-control @error('bdate') is-invalid @enderror" placeholder="Укажите дату рождения клиента..." value="{{ old('bdate', $client->bdate) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Страна <span style="color: red">*</span></strong>
                        <br>
                        <select class="form-control @error('id_country') is-invalid @enderror" name="id_country" id="id_country">
                            <option value="0" {{ old('id_country', $client->id_country) == '0' ? 'selected' : '' }}>Выберите из списка...</option>
                            {{-- Массив коллекций стран только с полями "id" и "name" --}}
                            @if(!empty($countriesListData))
                                @foreach($countriesListData as $countryItem)
                                    <option value="{{ $countryItem->id }}" {{ old('id_country', $client->id_country) == $countryItem->id ? 'selected' : '' }}>
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
                            <option value="0" {{ old('id_city', $client->id_city) == '0' ? 'selected' : '' }}>Выберите из списка...</option>
                            {{-- Массив коллекций городов только с полями "id" и "name" --}}
                            @if(!empty($citiesListData))
                                @foreach($citiesListData as $cityItem)
                                    <option value="{{ $cityItem->id }}" {{ old('id_city', $client->id_city) == $cityItem->id ? 'selected' : '' }}>
                                        {{ $cityItem->name }} ({{ $cityItem->country_name }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Адрес</strong>
                        <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Укажите адрес клиента..." value="{{ old('address', $client->address) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Комментарий менеджера</strong>
                        <textarea
                            class="form-control @error('manager_comment') is-invalid @enderror"
                            id="manager_comment"
                            name="manager_comment"
                            rows="3"
                            placeholder="Укажите комментарий о клиенте..."
                        >{{ old('manager_comment', $client->manager_comment) }}</textarea>
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
