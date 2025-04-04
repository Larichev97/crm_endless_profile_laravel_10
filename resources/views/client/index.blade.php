@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Клиенты'])

    <div class="container-fluid py-4">
        @include('components.alert')

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-4">
                                <h5>Список клиентов</h5>
                            </div>
                            <div class="col-8 d-flex">
                                <a class="btn btn-success" href="{{ route('admin.clients.create') }}" style="margin-left: auto">Добавить клиента</a>
                                <a class="btn btn-danger" href="{{ route('admin.clients.index') }}" style="margin-left: 15px;">Очистить фильтр</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if(!empty($displayedFields) && is_array($displayedFields))
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        @include('components.table-with-data.table-filter-header', ['indexRouteName' => 'admin.clients.index'])
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <form id="clients_filter_form" action="{{ route('admin.clients.index') }}" method="GET">
                                                <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                                                <input type="hidden" name="sort_way" value="{{ $sortWay }}">

                                                @php $isObjectFilterFiles = (isset($filterFieldsObject) && is_object($filterFieldsObject)); @endphp

                                                @foreach($displayedFields as $displayedFieldArray)
                                                    @if(!empty($displayedFieldArray) && is_array($displayedFieldArray))
                                                    <th class="text-center font-weight-bolder">
                                                        @php $currentFieldName = (string) $displayedFieldArray['field']; @endphp

                                                        {{-- Выпадающий список --}}
                                                        @if('select' === $displayedFieldArray['field_input_type'])
                                                            <select class="form-control" name="filter_{{ $currentFieldName }}" id="filter_{{ $currentFieldName }}">
                                                                <option value="0" @if($isObjectFilterFiles && empty($filterFieldsObject->$currentFieldName)) selected="selected" @endif>Выберите из списка...</option>

                                                                @if('id_country' === $currentFieldName && !empty($countriesListData))
                                                                    @foreach($countriesListData as $countryItem)
                                                                        <option value="{{ $countryItem->id }}" @if($isObjectFilterFiles && !empty($filterFieldsObject->$currentFieldName) && (int) $filterFieldsObject->$currentFieldName == (int) $countryItem->id) selected="selected" @endif>
                                                                            {{ $countryItem->name }}
                                                                        </option>
                                                                    @endforeach
                                                                @elseif('id_city' === $currentFieldName && !empty($citiesListData))
                                                                    @foreach($citiesListData as $cityItem)
                                                                        <option value="{{ $cityItem->id }}" @if($isObjectFilterFiles && !empty($filterFieldsObject->$currentFieldName) && (int) $filterFieldsObject->$currentFieldName == (int) $cityItem->id) selected="selected" @endif>
                                                                            {{ $cityItem->name }} ({{ $cityItem->country_name }})
                                                                        </option>
                                                                    @endforeach
                                                                @elseif('id_status' === $currentFieldName && !empty($statusesListData))
                                                                    @foreach($statusesListData as $statusItem)
                                                                        <option value="{{ $statusItem['id'] }}" @if($isObjectFilterFiles && !empty($filterFieldsObject->$currentFieldName) && (int) $filterFieldsObject->$currentFieldName == (int) $statusItem['id']) selected="selected" @endif>
                                                                            {{ $statusItem['name'] }}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        @else
                                                            <input type="{{ $displayedFieldArray['field_input_type'] }}" name="filter_{{ $currentFieldName }}" id="filter_{{ $currentFieldName }}" class="form-control" value="@if($isObjectFilterFiles && !empty($filterFieldsObject->$currentFieldName)){{ $filterFieldsObject->$currentFieldName }}@endif">
                                                        @endif
                                                    </th>
                                                    @endif
                                                @endforeach

                                                <th class="text-center text-secondary font-weight-bolder">
                                                    <button type="submit" class="btn btn-info" style="margin-bottom: 0;">Фильтр</button>
                                                </th>
                                            </form>
                                        </tr>
                                        @foreach ($clients as $client)
                                            <tr>
                                                @foreach($displayedFields as $displayedFieldArray)
                                                    @php $currentFieldName = (string) $displayedFieldArray['field']; @endphp

                                                    {{-- Уникальный вывод значения у поля: --}}
                                                    @if('id_country' === $currentFieldName)
                                                        <td class="align-middle text-center">
                                                        @if($client->id_country && !empty(trim($client->country->flag_file_name)))
                                                            <img src="{{ asset('/images/countries/'.$client->id_country.'/'.$client->country->flag_file_name) }}" alt="{{ $client->country->iso_code }}" width="32px" height="20px" style="box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4);">
                                                        @else
                                                            --
                                                        @endif
                                                        </td>
                                                    @elseif('id_city' === $currentFieldName)
                                                        <td class="align-middle text-center">{{ $client->city->name }}</td>
                                                    @elseif('id_status' === $currentFieldName)
                                                        <td class="align-middle text-center text-sm">
                                                            <span class="badge badge-sm bg-gradient-{{ $client->getStatusGradientColor(intval($client->id_status)) }}" style="width: 100px; padding-top: 0.74rem; padding-bottom: 0.74rem;">{{ $client->statusName }}</span>
                                                        </td>
                                                    {{-- Вывод значения из свойства модели: --}}
                                                    @else
                                                        <td class="align-middle text-center">{{ $client->$currentFieldName }}</td>
                                                    @endif
                                                @endforeach

                                                @include('components.table-with-data.table-row-actions', ['entityId' => $client->id, 'destroyRouteName' => 'admin.clients.destroy', 'showRouteName' => 'admin.clients.show', 'editRouteName' => 'admin.clients.edit',])
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {{-- Custom pagination template: resources/views/components/pagination.blade.php --}}
                                {!! $clients->links('components.pagination') !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @include('components.filter-script', ['idFilterForm' => 'clients_filter_form'])
@endpush
