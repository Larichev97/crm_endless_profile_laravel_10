@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Клиенты'])

    <div class="container-fluid py-4">
        @include('components.alert')

        @php $isObjectFilterFiles = (isset($filterFieldsObject) && is_object($filterFieldsObject)); @endphp

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="">Список клиентов</h6>
                            </div>
                            <div class="col-6 d-flex">
                                <a class="btn btn-success" href="{{ route('clients.create') }}" style="margin-left: auto">Добавить клиента</a>
                                <a class="btn btn-danger" href="{{ route('clients.index') }}" style="margin-left: 15px;">Очистить фильтр</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">#</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Имя</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Фамилия</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Отчество</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Email</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Моб. номер</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Страна</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Город</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Статус</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <form id="filter_form" action="{{ route('clients.index') }}" method="GET">
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
                                                <input type="email" name="filter_email" id="filter_email" class="form-control" value="@if($isObjectFilterFiles && !empty($filterFieldsObject->email)){{ $filterFieldsObject->email }}@endif">
                                            </th>
                                            <th class="text-center font-weight-bolder">
                                                <input type="text" name="filter_phone_number" id="filter_phone_number" class="form-control" value="@if($isObjectFilterFiles && !empty($filterFieldsObject->filter_phone_number)){{ $filterFieldsObject->filter_phone_number }}@endif">
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
                                                <select class="form-control" name="filter_id_city" id="filter_id_city">
                                                    <option value="0" @if($isObjectFilterFiles && empty($filterFieldsObject->id_city)) selected="selected" @endif>Выберите из списка...</option>
                                                    {{-- Массив коллекций стран только с полями "id" и "name" --}}
                                                    @if(!empty($citiesListData))
                                                        @foreach($citiesListData as $cityItem)
                                                            <option value="{{ $cityItem->id }}" @if($isObjectFilterFiles && !empty($filterFieldsObject->id_city) && (int) $filterFieldsObject->id_city == (int) $cityItem->id) selected="selected" @endif>
                                                                {{ $cityItem->name }} ({{ $cityItem->country_name }})
                                                            </option>
                                                        @endforeach
                                                    @endif
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
                                    @foreach ($clients as $client)
                                        <tr>
                                            <td class="align-middle text-center">{{ $client->id }}</td>
                                            <td class="align-middle text-center">{{ $client->firstname }}</td>
                                            <td class="align-middle text-center">{{ $client->lastname }}</td>
                                            <td class="align-middle text-center">{{ $client->surname }}</td>
                                            <td class="align-middle text-center">{{ $client->email }}</td>
                                            <td class="align-middle text-center">{{ $client->phone_number }}</td>
                                            <td class="align-middle text-center">
                                                @if($client->id_country && !empty(trim($client->country->flag_file_name)))
                                                    <img src="{{ asset('/images/countries/'.$client->id_country.'/'.$client->country->flag_file_name) }}" alt="{{ $client->country->iso_code }}" width="32px" height="20px" style="box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4);">
                                                @else
                                                    --
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">{{ $client->city->name }}</td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-{{ $client->getStatusGradientColor(intval($client->id_status)) }}" style="width: 100px; padding-top: 0.74rem; padding-bottom: 0.74rem;">{{ $client->statusName }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST">
                                                    <a class="btn btn-info btn-sm" style="margin-bottom: 0; padding-left: 12px; padding-right: 12px;" href="{{ route('clients.show', $client->id) }}"><i class="fas fa-eye"></i></a>
                                                    <a class="btn btn-primary btn-sm" style="margin-bottom: 0; padding-left: 12px; padding-right: 12px;" href="{{ route('clients.edit', $client->id) }}"><i class="fas fa-edit"></i></a>

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
                            {!! $clients->links('components.pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @include('components.filter-script')
@endpush
