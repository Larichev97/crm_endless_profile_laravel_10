@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Редактирование заявки #'.$contactForm->id])

    <div class="container-fluid py-4 mt-5">
        <form class="card p-4" action="{{ route('contact-forms.update', $contactForm->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <a style="width: fit-content;" class="btn btn-dark" href="{{ route('contact-forms.index') }}">Назад</a>

            <div>
                @include('components.alert')
            </div>

            <div class="row">
                <input type="hidden" name="id_employee" class="form-control" value="{{ auth()->user()->id }}">

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Статус заявки</strong>
                        <br>
                        @if(!empty($statusesListData))
                            <select class="form-control @error('id_status') is-invalid @enderror" name="id_status" id="id_status">
                                <option value="0" {{ old('id_status', $contactForm->id_status) == '0' ? 'selected' : '' }}>Выберите из списка...</option>
                                @foreach($statusesListData as $statusItem)
                                    <option value="{{ $statusItem['id'] }}" {{ old('id_status', $contactForm->id_status) == $statusItem['id'] ? 'selected' : '' }}>{{ $statusItem['name'] }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Имя <span style="color: red">*</span></strong>
                        <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" placeholder="Укажите имя..." value="{{ old('firstname', $contactForm->firstname) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Фамилия</strong>
                        <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" placeholder="Укажите фамилию..." value="{{ old('lastname', $contactForm->lastname) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Email</strong>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Укажите email..." value="{{ old('email', $contactForm->email) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Номер телефона</strong>
                        <input type="text" id="phone_number" placeholder="+380 (__) ___-__-__" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $contactForm->phone_number) }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Комментарий</strong>
                        <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="5" placeholder="Укажите комментарий...">{{ old('comment', $contactForm->comment) }}</textarea>
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
@push('js')
    <script>
        /* Применяем маску к input для номера телефона ------------- */
        const phoneNumberInput = $('#phone_number');

        $(document).ready(function() {
            $(phoneNumberInput).inputmask("+380 (99) 999-99-99");
        });
        /* --------------------------------------------------------- */
    </script>
@endpush
