@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Просмотр заявки #'.$contactForm->id])

    <div class="container-fluid py-4">
        @include('components.alert')

        <div class="card shadow-lg mx-4">
            <div class="card-body p-3">
                <div class="row gx-4">
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">
                                {{ $contactForm->lastname }} {{ $contactForm->firstname }}
                            </h5>
                            <p class="mb-0 font-weight-bold text-sm">
                                <span class="badge badge-sm bg-gradient-{{ $contactForm->statusGradientColor }}">{{ $contactForm->statusName }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                        @if(!empty($contactForm->userWhoUpdated) && (int) $contactForm->userWhoUpdated->id > 0)
                            <div class="row">
                                <span class="text-left text-secondary">Редактировал: {{ $contactForm->userWhoUpdated->fullName }} ({{ $contactForm->updated_at->format('d.m.Y H:i:s') }})</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-md-4"><span class="text-uppercase text-sm">Информация о заявке</span></div>
                                <div class="col-md-8 d-flex align-items-center">
                                    <a href="{{ route('contact-forms.edit', $contactForm->id) }}" class="btn btn-primary btn-sm ms-auto"><i class="fas fa-edit" style="margin-right: 5px;"></i>Редактировать заявку</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstname" class="form-control-label">Имя</label>
                                        <input class="form-control" disabled="disabled" type="text" id="firstname" name="firstname" value="{{ $contactForm->firstname }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastname" class="form-control-label">Фамилия</label>
                                        <input class="form-control" disabled="disabled" type="text" id="lastname" name="lastname" value="{{ $contactForm->lastname }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-control-label">Email</label>
                                        <input class="form-control" disabled="disabled" type="text" id="email" name="email" value="{{ $contactForm->email }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number" class="form-control-label">Номер телефона</label>
                                        <input class="form-control" disabled="disabled" type="text" id="phone_number" name="phone_number" value="{{ $contactForm->phone_number }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="comment" class="form-control-label">Комментарий</label>
                                        <textarea class="form-control" id="comment" name="comment" disabled="disabled" rows="5">{{ $contactForm->comment }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-profile">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-md-12 align-items-center">
                                    <a href="{{ route('clients.create',
                                        [
                                            'id_contact_form' => $contactForm->id,
                                            'firstname' => $contactForm->firstname,
                                            'lastname' => $contactForm->lastname,
                                            'email' => $contactForm->email,
                                            'phone_number' => $contactForm->phone_number,
                                        ]
                                    ) }}" class="btn btn-success btn-sm w-100"><i class="fas fa-user" style="margin-right: 5px;"></i>Преобразовать в Клиента</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footers.auth.footer')
        </div>
    </div>
@endsection
