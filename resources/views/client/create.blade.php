@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Добавление клиента'])

    <div class="container-fluid py-4 mt-4">
        <form class="card p-4" action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <a style="width: fit-content;" class="btn btn-primary" href="{{ route('clients.index') }}"> Назад</a>

            <div>
                @include('components.alert')
            </div>

            <div class="row">
                <input type="hidden" name="id_status" class="form-control" value="@if(isset($idStatusNew)){{ intval($idStatusNew) }}@else{{ 1 }}@endif">
                <input type="hidden" name="id_user_add" class="form-control" value="{{ auth()->user()->id }}">
                <input type="hidden" name="id_user_update" class="form-control" value="0">

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Фото клиента</strong>
                        <input type="file" name="image" id="inputImage" class="form-control @error('image') is-invalid @enderror">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Имя <span style="color: red">*</span></strong>
                        <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" placeholder="Укажите имя клиента..." value="{{ old('firstname') }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Фамилия <span style="color: red">*</span></strong>
                        <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" placeholder="Укажите фамилию клиента..." value="{{ old('lastname') }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Отчество</strong>
                        <input type="text" name="surname" class="form-control @error('surname') is-invalid @enderror" placeholder="Укажите отчество клиента..." value="{{ old('surname') }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Email <span style="color: red">*</span></strong>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Укажите email клиента..." value="{{ old('email') }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Моб. телефон <span style="color: red">*</span></strong>
                        <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" placeholder="+380680000000" value="{{ old('phone_number') }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Дата рождения <span style="color: red">*</span></strong>
                        <input type="date" name="bdate" class="form-control @error('bdate') is-invalid @enderror" placeholder="Укажите дату рождения клиента..." value="{{ old('bdate') }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Страна <span style="color: red">*</span></strong>
                        <br>
                        <select class="form-control @error('id_country') is-invalid @enderror" name="id_country" id="id_country">
                            <option value="0" {{ old('id_country') == '0' ? 'selected' : '' }}>Выберите из списка...</option>
                            {{-- Массив коллекций стран только с полями "id" и "name" --}}
                            @if(!empty($countriesListData))
                                @foreach($countriesListData as $countryItem)
                                    <option value="{{ $countryItem->id }}" {{ old('id_country') == $countryItem->id ? 'selected' : '' }}>
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
                            <option value="0" {{ old('id_city') == '0' ? 'selected' : '' }}>Выберите из списка...</option>
                            <option value="1" {{ old('id_city') == '1' ? 'selected' : '' }}>Днепр</option>
                            <option value="2" {{ old('id_city') == '2' ? 'selected' : '' }}>Киев</option>
                            <option value="3" {{ old('id_city') == '3' ? 'selected' : '' }}>Харьков</option>
                            <option value="4" {{ old('id_city') == '4' ? 'selected' : '' }}>Запорожье</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Комментарий менеджера</strong>
                        <textarea class="form-control @error('manager_comment') is-invalid @enderror" id="manager_comment" name="manager_comment" rows="3" placeholder="Укажете комментарий о клиенте...">{{ old('manager_comment') }}</textarea>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-success btn-lg w-100">Добавить</button>
                </div>
            </div>

        </form>

        @include('layouts.footers.auth.footer')
    </div>
@endsection
