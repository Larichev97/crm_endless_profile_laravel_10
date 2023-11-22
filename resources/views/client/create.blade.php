@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Добавление клиента'])

    <div class="container-fluid py-4">
{{--        <div class="row">--}}
{{--            <div class="col-lg-12 margin-tb">--}}
{{--                <div class="pull-left">--}}
{{--                    <h2>Добавление клиента</h2>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        </div>--}}

        @if ($errors->any())
            <div class="card p-4 alert alert-danger">
                <strong>Упссс!</strong> С вашим вводом возникли некоторые проблемы.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="card p-4" action="{{ route('clients.store') }}" method="POST">
            @csrf

            <a style="width: fit-content;" class="btn btn-primary" href="{{ route('clients.index') }}"> Назад</a>

            <div class="row">
                <input type="text" name="id_status" class="form-control" hidden="hidden" value="1">

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Имя:</strong>
                        <input type="text" name="firstname" class="form-control" placeholder="Имя">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Фамилия:</strong>
                        <input type="text" name="lastname" class="form-control" placeholder="Фамилия">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Отчество:</strong>
                        <input type="text" name="surname" class="form-control" placeholder="Отчество">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Email:</strong>
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Моб. телефон:</strong>
                        <input type="text" name="phone_number" class="form-control" placeholder="380680000000">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Дата рождения:</strong>
                        <input type="date" name="bdate" class="form-control" placeholder="Дата рождения">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Страна:</strong>
                        <select name="id_country" id="id_country">
                            <option value="1">Украина</option>
                            <option value="2">Польша</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Город:</strong>
                        <select name="id_city" id="id_city">
                            <option value="1">Днепр</option>
                            <option value="2">Киев</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Комментарий менеджера:</strong>
                        <textarea class="form-control" style="height:150px" name="manager_comment" placeholder="Комментарий менеджера"></textarea>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </div>
            </div>

        </form>

        @include('layouts.footers.auth.footer')
    </div>
@endsection
