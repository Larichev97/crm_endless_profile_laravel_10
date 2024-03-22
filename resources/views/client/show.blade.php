@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Просмотр клиента'])

    <div class="container-fluid py-4">
        @include('components.alert')
        <div class="card shadow-lg mx-4">
            <div class="card-body p-3">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative">
                            @if (!empty($clientPhotoPath))
                                <img src="{{ asset($clientPhotoPath) }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                            @else
                                <img src="/img/user_avatar.png" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                            @endif
                        </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">
                                {{ $client->lastname }} {{ $client->firstname }} {{ $client->surname }}
                            </h5>
                            <p class="mb-0 font-weight-bold text-sm">
                                <span class="badge badge-sm bg-gradient-{{ $client->getStatusGradientColor(intval($client->id_status)) }}">{{ $client->statusName }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                        @if(!empty($client->userWhoCreated) && (int) $client->userWhoCreated->id > 0)
                            <div class="row">
                                <span class="text-left text-secondary">Создал: {{ $client->userWhoCreated->fullName }} @if(!empty($client->created_at)) ({{ $client->created_at->format('d.m.Y H:i:s') }}) @endif</span>
                            </div>
                        @endif
                        @if(!empty($client->userWhoUpdated) && (int) $client->userWhoUpdated->id > 0)
                            <div class="row">
                                <span class="text-left text-secondary">Редактировал: {{ $client->userWhoUpdated->fullName }} @if(!empty($client->updated_at)) {{ $client->updated_at->format('d.m.Y H:i:s') }}) @endif</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-md-6"><h5>Информация о клиенте</h5></div>
                                <div class="col-md-6 d-flex align-items-center">
                                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn-sm ms-auto">Редактировать</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="client_email" class="form-control-label">Email</label>
                                        <input disabled="disabled" class="form-control" type="email" id="client_email" name="email" value="{{ old('email', $client->email) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="client_phone_number" class="form-control-label">Моб. телефон</label>
                                        <input disabled="disabled" class="form-control" type="text" id="client_phone_number" name="phone_number" value="{{ old('phone_number', $client->phone_number) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="client_id_country" class="form-control-label">Страна</label>
                                        <input disabled="disabled" class="form-control" type="text" id="client_id_country" name="id_country" value="{{ $client->country->name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="client_id_city" class="form-control-label">Город</label>
                                        <input disabled="disabled" class="form-control" type="text" id="client_id_city" name="id_city" value="{{ old('id_city', $client->city->name) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="client_bdate" class="form-control-label">Дата рождения</label>
                                        <input disabled="disabled" class="form-control" type="text" id="client_bdate" name="bdate" value="{{ old('bdate', $client->birthDateFormatted) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="client_created_at" class="form-control-label">Дата регистрации</label>
                                        <input disabled="disabled" class="form-control" type="text" id="client_created_at" name="created_at" value="{{ old('created_at', $client->createdAtFormatted) }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="client_address" class="form-control-label">Адрес</label>
                                        <input disabled="disabled" class="form-control" type="text" id="client_address" name="address" value="{{ old('address', $client->address) }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="client_manager_comment" class="form-control-label">Комментарий менеджера</label>
                                        <textarea disabled="disabled" rows="5" class="form-control" id="client_manager_comment" name="manager_comment">{{ old('manager_comment', $client->manager_comment) }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card card-profile">
                        <div class="card-header text-center">
                            <h5>Список связанных QR-профилей</h5>
                        </div>
                        <div class="card-body pt-0">
                            @if(!empty($client->qrProfiles))
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">ID</th>
                                                <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">ФИО</th>
                                                <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">QR-код</th>
                                                <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Статус</th>
                                                <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Действия</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($client->qrProfiles as $qrItem)
                                            <tr>
                                                <td class="align-middle text-center">{{ $qrItem->id }}</td>
                                                <td class="align-middle text-center">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $qrItem->fullName }}</h6>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    @if(!empty(trim($qrItem->qr_code_file_name)))
                                                        <i class="fas fa-check" style="color: #2dce89"></i>
                                                    @else
                                                        <i class="fas fa-ban" style="color: #f5365c"></i>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <span class="badge badge-sm bg-gradient-{{ $qrItem->getStatusGradientColor(intval($qrItem->id_status)) }}" style="width: 100px; padding-top: 0.74rem; padding-bottom: 0.74rem;">{{ $qrItem->statusName }}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <form action="{{ route('qrs.destroy', $qrItem->id) }}" method="POST">
                                                        <a class="btn btn-info btn-sm" style="margin-bottom: 0; padding-left: 12px; padding-right: 12px;" href="{{ route('qrs.show', $client->id) }}"><i class="fas fa-eye"></i></a>
                                                        <a class="btn btn-primary btn-sm" style="margin-bottom: 0; padding-left: 12px; padding-right: 12px;" href="{{ route('qrs.edit', $client->id) }}"><i class="fas fa-edit"></i></a>

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-danger btn-sm" style="margin-bottom: 0; padding-left: 12px; padding-right: 12px;"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                    {{-- Custom pagination template: resources/views/components/pagination.blade.php --}}
{{--                                    {!! $client->qrs->links('components.pagination') !!}--}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footers.auth.footer')
        </div>
    </div>
@endsection
