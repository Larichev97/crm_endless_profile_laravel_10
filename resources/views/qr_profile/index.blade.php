@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'QR-профили'])

    <div class="container-fluid py-4">
        @include('components.alert')

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
                                <a class="btn btn-danger" href="{{ route('qrs.index') }}" style="margin-left: 15px;">Очистить фильтр</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">#</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">ФИО</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Дата смерти</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Страна</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Принадлежит клиенту</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">QR-код</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Статус</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <form id="filter_form" action="{{ route('qrs.index') }}" method="GET">
                                        <th class="text-center font-weight-bolder">
                                            <input type="text" name="filter_id" id="filter_id" class="form-control" value="@if(isset($filterFieldsObject) && is_object($filterFieldsObject) && !empty($filterFieldsObject->id)){{ $filterFieldsObject->id }}@endif">
                                        </th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">

                                        </th>
                                        <th class="text-center font-weight-bolder">
                                            <input type="date" name="filter_death_date" id="filter_death_date" class="form-control" value="@if(isset($filterFieldsObject) && is_object($filterFieldsObject) && !empty($filterFieldsObject->date_death)){{ $filterFieldsObject->date_death }}@endif">
                                        </th>
                                        <th class="text-center font-weight-bolder">
                                            <select class="form-control" name="filter_id_country" id="filter_id_country">
                                                <option value="0" @if(isset($filterFieldsObject) && is_object($filterFieldsObject) && empty($filterFieldsObject->id_country)) selected="selected" @endif>Выберите из списка...</option>
                                                <option value="1" @if(isset($filterFieldsObject) && is_object($filterFieldsObject) && !empty($filterFieldsObject->id_country) && $filterFieldsObject->id_country == 1) selected="selected" @endif>Украина</option>
                                                <option value="2" @if(isset($filterFieldsObject) && is_object($filterFieldsObject) && !empty($filterFieldsObject->id_country) && $filterFieldsObject->id_country == 2) selected="selected" @endif>Польша</option>
                                            </select>
                                        </th>
                                        <th class="text-center font-weight-bolder">
                                            {{-- ВЫВОДИТЬ ВЫПАДАЮЩИЙ СПИСОК ИЗ БЭКА !!! --}}
                                            <input type="text" name="filter_id_client" id="filter_id_client" class="form-control" value="@if(isset($filterFieldsObject) && is_object($filterFieldsObject) && !empty($filterFieldsObject->id_client)){{ $filterFieldsObject->id_client }}@endif">
                                        </th>
                                        <th class="text-center font-weight-bolder">
                                            <select class="form-control" name="filter_with_qr_code" id="filter_with_qr_code">
                                                <option value="0" @if(isset($filterFieldsObject) && is_object($filterFieldsObject) && empty($filterFieldsObject->with_qr_code)) selected="selected" @endif>Выберите из списка...</option>
                                                <option value="1" @if(isset($filterFieldsObject) && is_object($filterFieldsObject) && !empty($filterFieldsObject->with_qr_code) && $filterFieldsObject->with_qr_code == 1) selected="selected" @endif>Да</option>
                                                <option value="2" @if(isset($filterFieldsObject) && is_object($filterFieldsObject) && !empty($filterFieldsObject->with_qr_code) && $filterFieldsObject->with_qr_code == 2) selected="selected" @endif>Нет</option>
                                            </select>
                                        </th>
                                        <th class="text-center font-weight-bolder">
                                            {{-- ВЫВОДИТЬ ВЫПАДАЮЩИЙ СПИСОК ИЗ БЭКА (ENUMS) !!! --}}
                                            <select class="form-control" name="filter_id_status" id="filter_id_status">
                                                <option value="0" @if(isset($filterFieldsObject) && is_object($filterFieldsObject) && empty($filterFieldsObject->id_status)) selected="selected" @endif>Выберите из списка...</option>
                                                <option value="1" @if(isset($filterFieldsObject) && is_object($filterFieldsObject) && !empty($filterFieldsObject->id_status) && $filterFieldsObject->id_status == 1) selected="selected" @endif>Новый</option>
                                                <option value="2" @if(isset($filterFieldsObject) && is_object($filterFieldsObject) && !empty($filterFieldsObject->id_status) && $filterFieldsObject->id_status == 2) selected="selected" @endif>Создаётся</option>
                                                <option value="3" @if(isset($filterFieldsObject) && is_object($filterFieldsObject) && !empty($filterFieldsObject->id_status) && $filterFieldsObject->id_status == 3) selected="selected" @endif>Готов</option>
                                                <option value="4" @if(isset($filterFieldsObject) && is_object($filterFieldsObject) && !empty($filterFieldsObject->id_status) && $filterFieldsObject->id_status == 4) selected="selected" @endif>Получен</option>
                                                <option value="5" @if(isset($filterFieldsObject) && is_object($filterFieldsObject) && !empty($filterFieldsObject->id_status) && $filterFieldsObject->id_status == 5) selected="selected" @endif>Потерян</option>
                                                <option value="6" @if(isset($filterFieldsObject) && is_object($filterFieldsObject) && !empty($filterFieldsObject->id_status) && $filterFieldsObject->id_status == 6) selected="selected" @endif>Старый URL</option>
                                            </select>
                                        </th>

                                        <th class="text-center text-secondary font-weight-bolder">
                                            <button type="submit" class="btn btn-info" style="margin-bottom: 0;">Фильтр</button>
                                        </th>
                                    </form>
                                </tr>
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
                                            @if($qrProfile->id_country && !empty(trim($qrProfile->country->flag_file_name)))
                                                <img src="{{ asset('storage/images/countries/'.$qrProfile->id_country.'/'.$qrProfile->country->flag_file_name) }}" alt="{{ $qrProfile->country->iso_code }}" width="32px" height="20px" style="box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4);">
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">{{ $qrProfile->client->fullName }}</td>
                                        <td class="align-middle text-center text-sm">
                                            @if(!empty(trim($qrProfile->qr_code_file_name)))
                                                <i class="fas fa-check" style="color: #2dce89"></i>
                                            @else
                                                <i class="fas fa-ban" style="color: #f5365c"></i>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="badge badge-sm bg-gradient-{{ $qrProfile->getStatusGradientColor(intval($qrProfile->id_status)) }}" style="width: 100px; padding-top: 0.74rem; padding-bottom: 0.74rem;">{{ $qrProfile->statusName }}</span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <form action="{{ route('qrs.destroy', $qrProfile->id) }}" method="POST">
                                                <a class="btn btn-info btn-sm" href="{{ route('qrs.show', $qrProfile->id) }}" style="margin-bottom: 0; padding-left: 12px; padding-right: 12px;"><i class="fas fa-eye"></i></a>
                                                <a class="btn btn-primary btn-sm" href="{{ route('qrs.edit', $qrProfile->id) }}" style="margin-bottom: 0; padding-left: 12px; padding-right: 12px;"><i class="fas fa-edit"></i></a>

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
        document.addEventListener('DOMContentLoaded', handleFilterFormSubmit);

        /**
         *  Метод проверяет наличие заполенных полей фильтров и добавляет их к URL для редиректа
         */
        function handleFilterFormSubmit() {
            const filterForm = document.getElementById('filter_form');

            if (!filterForm) return;

            filterForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(this);
                const filteredData = {};

                // Пропускаем пустые поля формы Фильтра:
                formData.forEach(function(value, key) {
                    if (value !== '' && value !== '0') {
                        filteredData[key] = value;
                    }
                });

                const queryParameters = new URLSearchParams(filteredData).toString();
                const action = this.getAttribute('action');

                window.location.href = `${action}?${queryParameters}`;
            });
        }
    </script>
@endpush
