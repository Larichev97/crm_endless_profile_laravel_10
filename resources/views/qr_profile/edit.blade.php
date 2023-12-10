@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Редактирование клиента'])

    <div class="container-fluid py-4">
        <div id="alert">
            @include('components.alert')
        </div>

        <form class="card p-4" action="{{ route('clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <a style="width: fit-content;" class="btn btn-primary" href="{{ route('clients.index') }}"> Назад</a>

            <div class="row">
                <input type="hidden" name="photo_name" class="form-control" value="{{ $client->photo_name }}">
                <input type="hidden" name="id_client" class="form-control" value="{{ $client->id }}">
                <input type="hidden" name="id_user_add" class="form-control" value="{{ $client->id_user_add }}">
                <input type="hidden" name="id_user_update" class="form-control" value="{{ auth()->user()->id }}">

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Текущее фото клиента:</strong>
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
                        <strong>Статус:</strong>
                        <br>
                        @if(!empty($statuses_list_data))
                        <select name="id_status" id="id_status">
                            @foreach($statuses_list_data as $status_item)
                                <option value="{{ $status_item['id'] }}" {{ old('id_status', $client->id_status) == $status_item['id'] ? 'selected' : '' }}>{{ $status_item['name'] }}</option>
                            @endforeach
                        </select>
                        @endif
                        @error('id_status') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Имя:</strong>
                        <input type="text" name="firstname" class="form-control" placeholder="Имя" value="{{ old('firstname', $client->firstname) }}">
                        @error('firstname') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Фамилия:</strong>
                        <input type="text" name="lastname" class="form-control" placeholder="Фамилия" value="{{ old('lastname', $client->lastname) }}">
                        @error('lastname') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Отчество:</strong>
                        <input type="text" name="surname" class="form-control" placeholder="Отчество" value="{{ old('surname', $client->surname) }}">
                        @error('surname') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Email:</strong>
                        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email', $client->email) }}">
                        @error('email') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Моб. телефон:</strong>
                        <input type="text" name="phone_number" class="form-control" placeholder="380680000000" value="{{ old('phone_number', $client->phone_number) }}">
                        @error('phone_number') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Дата рождения:</strong>
                        <input type="date" name="bdate" class="form-control" placeholder="Укажите дату рождения" value="{{ old('bdate', $client->bdate) }}">
                        @error('bdate') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Страна:</strong>
                        <br>
                        <select name="id_country" id="id_country">
                            <option value="1" {{ old('id_country', $client->id_country) == 1 ? 'selected' : '' }}>Украина</option>
                            <option value="2" {{ old('id_country', $client->id_country) == 2 ? 'selected' : '' }}>Польша</option>
                        </select>
                        @error('id_country') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Город:</strong>
                        <br>
                        <select name="id_city" id="id_city">
                            <option value="1" {{ old('id_city', $client->id_city) == 1 ? 'selected' : '' }}>Днепр</option>
                            <option value="2" {{ old('id_city', $client->id_city) == 2 ? 'selected' : '' }}>Киев</option>
                        </select>
                        @error('id_city') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Комментарий менеджера:</strong>
                        <textarea
                            class="form-control"
                            id="manager_comment"
                            name="manager_comment"
                            rows="3"
                            placeholder="Комментарий менеджера..."
                        >{{ old('manager_comment', $client->manager_comment) }}</textarea>
                        @error('manager_comment') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
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
