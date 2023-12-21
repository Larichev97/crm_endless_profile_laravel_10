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
                        <input type="text" name="firstname" class="form-control" placeholder="Имя">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Фамилия <span style="color: red">*</span></strong>
                        <input type="text" name="lastname" class="form-control" placeholder="Фамилия">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Отчество</strong>
                        <input type="text" name="surname" class="form-control" placeholder="Отчество">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Email <span style="color: red">*</span></strong>
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Моб. телефон <span style="color: red">*</span></strong>
                        <input type="text" name="phone_number" class="form-control" placeholder="380680000000">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Дата рождения <span style="color: red">*</span></strong>
                        <input type="date" name="bdate" class="form-control" placeholder="Укажите дату рождения">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Страна <span style="color: red">*</span></strong>
                        <br>
                        <select class="form-control" name="id_country" id="id_country">
                            <option value="1">Украина</option>
{{--                            <option value="2">Польша</option>--}}
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Город <span style="color: red">*</span></strong>
                        <br>
                        <select class="form-control" name="id_city" id="id_city">
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
                        <textarea class="form-control" id="exampleFormControlTextarea1" name="manager_comment" rows="3" placeholder="Комментарий менеджера..."></textarea>
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
