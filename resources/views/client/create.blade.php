@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Добавление клиента'])

    <div class="container-fluid py-4 mt-5">
        <form class="card p-4" action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <a style="width: fit-content;" class="btn btn-dark" href="{{ route('clients.index') }}">Назад</a>

            <div>
                @include('components.alert')
            </div>

            <div class="row">
                <input type="hidden" name="id_status" class="form-control" value="@if(isset($idStatusNew)){{ intval($idStatusNew) }}@else{{ 1 }}@endif">
                <input type="hidden" name="id_user_add" class="form-control" value="{{ auth()->user()->id }}">
                <input type="hidden" name="id_user_update" class="form-control" value="{{ auth()->user()->id }}">
                <input type="hidden" name="id_contact_form" class="form-control" value="@if(!empty($id_contact_form)){{ intval($id_contact_form) }}@else{{ 0 }}@endif">

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Фото клиента</strong>
                        <input type="file" name="image" id="inputImage" class="form-control @error('image') is-invalid @enderror">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Имя <span style="color: red">*</span></strong>
                        <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" placeholder="Укажите имя клиента..." value="@if(!empty($firstname) && empty(old('firstname'))){{ $firstname }}@else{{ old('firstname') }}@endif">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Фамилия <span style="color: red">*</span></strong>
                        <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" placeholder="Укажите фамилию клиента..." value="@if(!empty($lastname) && empty(old('lastname'))){{ $lastname }}@else{{ old('lastname') }}@endif">
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
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Укажите email клиента..." value="@if(!empty($email) && empty(old('email'))){{ $email }}@else{{ old('email') }}@endif">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Моб. телефон <span style="color: red">*</span></strong>
                        <input type="text" id="client_phone_number" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" placeholder="+380 (__) ___-__-__" value="@if(!empty($phone_number) && empty(old('phone_number'))){{ $phone_number }}@else{{ old('phone_number') }}@endif">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Дата рождения</strong>
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
                            {{-- Массив коллекций городов только с полями "id" и "name" --}}
                            @if(!empty($citiesListData))
                                @foreach($citiesListData as $cityItem)
                                    <option value="{{ $cityItem->id }}" {{ old('id_city') == $cityItem->id ? 'selected' : '' }}>
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
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Укажите адрес клиента..." value="{{ old('address') }}">
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
@push('js')
    <script>
        const clientPhoneNumberInput = $('#client_phone_number');

        $(document).ready(function() {
            $(clientPhoneNumberInput).inputmask("+380 (99) 999-99-99");
        });
    </script>
@endpush
