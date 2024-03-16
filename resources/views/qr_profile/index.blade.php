@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'QR-профили'])

    <div class="container-fluid py-4">
        @include('components.alert')

        @php $isObjectFilterFiles = (isset($filterFieldsObject) && is_object($filterFieldsObject)); @endphp

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-6">
                                <h5>Список QR-профилей</h5>
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
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Имя</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Фамилия</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Отчество</th>
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
                                    <form id="qrs_filter_form" action="{{ route('qrs.index') }}" method="GET">
                                        <th class="text-center font-weight-bolder">
                                            <input type="text" name="filter_id" id="filter_id" class="form-control" value="@if($isObjectFilterFiles && !empty($filterFieldsObject->id)){{ $filterFieldsObject->id }}@endif">
                                        </th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">
                                            <input type="text" name="filter_firstname" id="filter_firstname" class="form-control" value="@if($isObjectFilterFiles && !empty($filterFieldsObject->filter_firstname)){{ $filterFieldsObject->filter_firstname }}@endif">
                                        </th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">
                                            <input type="text" name="filter_lastname" id="filter_lastname" class="form-control" value="@if($isObjectFilterFiles && !empty($filterFieldsObject->filter_lastname)){{ $filterFieldsObject->filter_lastname }}@endif">
                                        </th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">
                                            <input type="text" name="filter_surname" id="filter_surname" class="form-control" value="@if($isObjectFilterFiles && !empty($filterFieldsObject->filter_surname)){{ $filterFieldsObject->filter_surname }}@endif">
                                        </th>
                                        <th class="text-center font-weight-bolder">
                                            <input type="date" name="filter_death_date" id="filter_death_date" class="form-control" value="@if($isObjectFilterFiles && !empty($filterFieldsObject->death_date)){{ $filterFieldsObject->death_date }}@endif">
                                        </th>
                                        <th class="text-center font-weight-bolder">
                                            <select class="form-control" name="filter_id_country" id="filter_id_country">
                                                <option value="0" @if($isObjectFilterFiles && empty($filterFieldsObject->id_country)) selected="selected" @endif>Выберите из списка...</option>
                                                {{-- Массив коллекций стран только с полями "id" и "name" --}}
                                                @if(!empty($countriesListData))
                                                    @foreach($countriesListData as $countryItem)
                                                        <option value="{{ $countryItem->id }}" @if($isObjectFilterFiles && !empty($filterFieldsObject->id_country) && (int) $filterFieldsObject->id_country == (int) $countryItem->id) selected="selected" @endif>
                                                            {{ $countryItem->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </th>
                                        <th class="text-center font-weight-bolder">
                                            <select class="form-control" name="filter_id_client" id="filter_id_client">
                                                <option value="0" @if($isObjectFilterFiles && empty($filterFieldsObject->id_client)) selected="selected" @endif>Выберите клиента из списка...</option>
                                                {{-- Массив коллекций клиентов только с полями "id" и "name" --}}
                                                @if(!empty($clientsListData))
                                                    @foreach($clientsListData as $clientItem)
                                                        <option value="{{ $clientItem->id }}" @if($isObjectFilterFiles && !empty($filterFieldsObject->id_client) && (int) $filterFieldsObject->id_client == (int) $clientItem->id) selected="selected" @endif>
                                                            {{ $clientItem->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </th>
                                        <th class="text-center font-weight-bolder">
                                            <select class="form-control" name="filter_with_qr_code" id="filter_with_qr_code">
                                                <option value="0" @if($isObjectFilterFiles && empty($filterFieldsObject->with_qr_code)) selected="selected" @endif>Выберите из списка...</option>
                                                <option value="1" @if($isObjectFilterFiles && !empty($filterFieldsObject->with_qr_code) && (int) $filterFieldsObject->with_qr_code == 1) selected="selected" @endif>Да</option>
                                                <option value="2" @if($isObjectFilterFiles && !empty($filterFieldsObject->with_qr_code) && (int) $filterFieldsObject->with_qr_code == 2) selected="selected" @endif>Нет</option>
                                            </select>
                                        </th>
                                        <th class="text-center font-weight-bolder">
                                            @if(!empty($statusesListData))
                                                <select class="form-control" name="filter_id_status" id="filter_id_status">
                                                    <option value="0" @if($isObjectFilterFiles && empty($filterFieldsObject->id_status)) selected="selected" @endif>Выберите из списка...</option>
                                                    @foreach($statusesListData as $statusItem)
                                                        <option value="{{ $statusItem['id'] }}" @if($isObjectFilterFiles && !empty($filterFieldsObject->id_status) && (int) $filterFieldsObject->id_status == (int) $statusItem['id']) selected="selected" @endif>{{ $statusItem['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </th>
                                        <th class="text-center text-secondary font-weight-bolder">
                                            <button type="submit" class="btn btn-info" style="margin-bottom: 0;">Фильтр</button>
                                        </th>
                                    </form>
                                </tr>
                                @foreach ($qrProfiles as $qrProfile)
                                    <tr>
                                        <td class="align-middle text-center">{{ $qrProfile->id }}</td>
                                        <td class="align-middle text-center">{{ $qrProfile->firstname }}</td>
                                        <td class="align-middle text-center">{{ $qrProfile->lastname }}</td>
                                        <td class="align-middle text-center">{{ $qrProfile->surname }}</td>
                                        <td class="align-middle text-center">{{ $qrProfile->deathDateFormatted }}</td>
                                        <td class="align-middle text-center">
                                            @if($qrProfile->id_country && !empty(trim($qrProfile->country->flag_file_name)))
                                                <img src="{{ asset('/images/countries/'.$qrProfile->id_country.'/'.$qrProfile->country->flag_file_name) }}" alt="{{ $qrProfile->country->iso_code }}" width="32px" height="20px" style="box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4);">
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
    @include('components.filter-script', ['idFilterForm' => 'qrs_filter_form'])
@endpush
