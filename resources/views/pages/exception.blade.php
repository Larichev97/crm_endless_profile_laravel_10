@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Ошибка'])

    <div class="container-fluid py-4 mt-5">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8">
                <div style="padding: 40px 15px; text-align: center;">
                    <h1 class="text-gradient text-danger">Упсссс!</h1>
                    <h2 class="text-gradient text-danger">Что-то пошло не так :(</h2>
                    <div class="error-details mt-5" style="text-align: left!important;">
                        @if(isset($error_code) && is_numeric($error_code))
                            <h4><span class="text-gradient text-danger">КОД ОШИБКИ:</span> <span class="text-gradient text-dark">{{ $error_code }}</span></h4>
                        @endif

                        @if(!empty($error_message))
                            <h4><span class="text-gradient text-danger">ОПИСАНИЕ ОШИБКИ:</span> <span class="text-gradient text-dark">{{ $error_message }}</span></h4>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-2"></div>
        </div>

        @include('layouts.footers.auth.footer')
    </div>
@endsection
