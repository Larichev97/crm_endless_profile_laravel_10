@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Города'])

    <div class="container-fluid py-4">
        @include('components.alert')

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-6">
                                <h5>Список городов</h5>
                            </div>
                            <div class="col-6 d-flex">
                                <a class="btn btn-success" href="{{ route('cities.create') }}" style="margin-left: auto">Добавить город</a>
                                <a class="btn btn-danger" href="{{ route('cities.index') }}" style="margin-left: 15px;">Очистить фильтр</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if(!empty($displayedFields) && is_array($displayedFields))
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        @include('components.table-with-data.table-filter-header', ['indexRouteName' => 'cities.index'])
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <form id="cities_filter_form" action="{{ route('cities.index') }}" method="GET">
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
                                                                @elseif('is_active' === $currentFieldName)
                                                                    <option value="1" @if($isObjectFilterFiles && !empty($filterFieldsObject->$currentFieldName) && (int) $filterFieldsObject->$currentFieldName == 1) selected="selected" @endif>Да</option>
                                                                    <option value="2" @if($isObjectFilterFiles && !empty($filterFieldsObject->$currentFieldName) && (int) $filterFieldsObject->$currentFieldName == 2) selected="selected" @endif>Нет</option>
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
                                    @foreach ($cities as $cityItem)
                                        <tr>
                                            @foreach($displayedFields as $displayedFieldArray)
                                                @php $currentFieldName = (string) $displayedFieldArray['field']; @endphp

                                                {{-- Уникальный вывод значения у поля: --}}
                                                @if('id_country' === $currentFieldName)
                                                    <td class="align-middle text-center">
                                                        @if($cityItem->id_country && !empty(trim($cityItem->country->flag_file_name)))
                                                           {{ $cityItem->country->name }} <img src="{{ asset('/images/countries/'.$cityItem->id_country.'/'.$cityItem->country->flag_file_name) }}" alt="{{ $cityItem->country->iso_code }}" width="32px" height="20px" style="box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4); margin-left: 5px;">
                                                        @else
                                                            {{ $cityItem->country->name }}
                                                        @endif
                                                    </td>
                                                @elseif('is_active' === $currentFieldName)
                                                    <td class="align-middle text-center text-sm">
                                                        @if($cityItem->is_active)
                                                            <i class="fas fa-check" style="color: #2dce89"></i>
                                                        @else
                                                            <i class="fas fa-ban" style="color: #f5365c"></i>
                                                        @endif
                                                    </td>
                                                {{-- Вывод значения из свойства модели: --}}
                                                @else
                                                    <td class="align-middle text-center">{{ $cityItem->$currentFieldName }}</td>
                                                @endif
                                            @endforeach

                                            @include('components.table-with-data.table-row-actions', ['entityId' => $cityItem->id, 'destroyRouteName' => 'cities.destroy', 'showRouteName' => 'cities.show', 'editRouteName' => 'cities.edit',])
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                {{-- Custom pagination template: resources/views/components/pagination.blade.php --}}
                                {!! $cities->links('components.pagination') !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @include('components.filter-script', ['idFilterForm' => 'cities_filter_form'])
@endpush
