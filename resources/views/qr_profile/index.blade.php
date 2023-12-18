@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'QR-профили'])

    <div class="container-fluid py-4">
        <div>
            @include('components.alert')
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="">Список QR-профилей</h6>
                            </div>
                            <div class="col-6 d-flex">
                                <a class="btn btn-success" href="{{ route('qrs.create') }}" style="margin-left: auto">Добавить QR-профиль</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">№</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7 ps-2">ФИО</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Дата смерти</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Страна</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Принадлежит клиенту</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Статус</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($qrProfiles as $qrProfile)
                                    <tr>
                                        <td class="align-middle text-center">{{ $qrProfile->id }}</td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $qrProfile->fullName }}</h6>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">{{ $qrProfile->deathDateFormatted }}</td>
                                        <td class="align-middle text-center">
                                            @php
                                                if ($qrProfile->id_status == 1) {
                                                    $flagName = 'UA';
                                                } else if ($qrProfile->id_status == 2) {
                                                    $flagName = 'PL';
                                                } else {
                                                    $flagName = 'US';
                                                }
                                            @endphp
                                            <img src="/img/icons/flags/{{ $flagName }}.png" alt="Флаг страны" width="32px" height="20px" style="box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4);">
                                        </td>
                                        <td class="align-middle text-center">{{ $qrProfile->client->fullName }}</td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="badge badge-sm bg-gradient-success" style="width: 100px; padding-top: 0.74rem; padding-bottom: 0.74rem;">{{ $qrProfile->statusName }}</span>
                                        </td>
                                        <td class="align-middle">
                                            <form action="{{ route('qrs.destroy', $qrProfile->id) }}" method="POST">
                                                <a class="btn btn-info btn-sm" href="{{ route('qrs.show', $qrProfile->id) }}" style="margin-bottom: 0;"><i class="fas fa-eye"></i></a>
                                                <a class="btn btn-primary btn-sm" href="{{ route('qrs.edit', $qrProfile->id) }}" style="margin-bottom: 0;"><i class="fas fa-edit"></i></a>

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm" style="margin-bottom: 0;"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{-- Custom pagination template: resources/views/components/pagination.blade.php --}}
                            {!! $qrProfiles->links('components.pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>

    </script>
@endpush
