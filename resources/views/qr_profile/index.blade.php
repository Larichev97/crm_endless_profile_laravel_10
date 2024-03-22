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
                            <div class="col-4">
                                <h5>Список QR-профилей</h5>
                            </div>
                            <div class="col-8 d-flex">
                                <a class="btn btn-success" href="{{ route('qrs.create') }}" style="margin-left: auto">Добавить QR-профиль</a>
                                <a class="btn btn-danger" href="{{ route('qrs.index') }}" style="margin-left: 15px;">Очистить фильтр</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if(!empty($displayedFields) && is_array($displayedFields))
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            @foreach($displayedFields as $displayedFieldArray)
                                                <th class="text-center text-dark text-xs font-weight-bolder">
                                                    <label for="filter_{{ $displayedFieldArray['field'] }}">{{ $displayedFieldArray['field_title'] }}</label>
                                                    <a href="{{ route('qrs.index', ['sort_by' => $displayedFieldArray['field'], 'sort_way' => 'desc']) }}"><i class="fa fa-arrow-down @if($sortBy === $displayedFieldArray['field'] && $sortWay === 'desc') text-warning @endif"></i></a>
                                                    <a href="{{ route('qrs.index', ['sort_by' => $displayedFieldArray['field'], 'sort_way' => 'asc']) }}"><i class="fa fa-arrow-up @if($sortBy === $displayedFieldArray['field'] && $sortWay === 'asc') text-warning @endif"></i></a>
                                                </th>
                                            @endforeach

                                            <th class="text-center text-secondary text-xs font-weight-bolder opacity-8">Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <form id="qrs_filter_form" action="{{ route('qrs.index') }}" method="GET">
                                            <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                                            <input type="hidden" name="sort_way" value="{{ $sortWay }}">

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
                                                                @elseif('id_client' === $currentFieldName && !empty($clientsListData))
                                                                    @foreach($clientsListData as $clientItem)
                                                                        <option value="{{ $clientItem->id }}" @if($isObjectFilterFiles && !empty($filterFieldsObject->id_client) && (int) $filterFieldsObject->id_client == (int) $clientItem->id) selected="selected" @endif>
                                                                            {{ $clientItem->name }}
                                                                        </option>
                                                                    @endforeach
                                                                @elseif('with_qr_code' === $currentFieldName)
                                                                    <option value="1" @if($isObjectFilterFiles && !empty($filterFieldsObject->$currentFieldName) && (int) $filterFieldsObject->$currentFieldName == 1) selected="selected" @endif>Да</option>
                                                                    <option value="2" @if($isObjectFilterFiles && !empty($filterFieldsObject->$currentFieldName) && (int) $filterFieldsObject->$currentFieldName == 2) selected="selected" @endif>Нет</option>
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
                                    @foreach ($qrProfiles as $qrProfile)
                                        <tr>
                                            @foreach($displayedFields as $displayedFieldArray)
                                                @php $currentFieldName = (string) $displayedFieldArray['field']; @endphp

                                                {{-- Уникальный вывод значения у поля: --}}
                                                @if('id_country' === $currentFieldName)
                                                    <td class="align-middle text-center">
                                                        @if($qrProfile->id_country && !empty(trim($qrProfile->country->flag_file_name)))
                                                            <img src="{{ asset('/images/countries/'.$qrProfile->id_country.'/'.$qrProfile->country->flag_file_name) }}" alt="{{ $qrProfile->country->iso_code }}" width="32px" height="20px" style="box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4);">
                                                        @else
                                                            --
                                                        @endif
                                                    </td>
                                                @elseif('id_client' === $currentFieldName)
                                                    <td class="align-middle text-center">{{ $qrProfile->client->fullName }}</td>
                                                @elseif('with_qr_code' === $currentFieldName)
                                                    <td class="align-middle text-center text-sm">
                                                        @if(!empty(trim($qrProfile->qr_code_file_name)))
                                                            <i class="fas fa-check" style="color: #2dce89"></i>
                                                        @else
                                                            <i class="fas fa-ban" style="color: #f5365c"></i>
                                                        @endif
                                                    </td>
                                                @elseif('id_status' === $currentFieldName)
                                                    <td class="align-middle text-center text-sm">
                                                        <span class="badge badge-sm bg-gradient-{{ $qrProfile->getStatusGradientColor(intval($qrProfile->id_status)) }}" style="width: 100px; padding-top: 0.74rem; padding-bottom: 0.74rem;">{{ $qrProfile->statusName }}</span>
                                                    </td>
                                                    {{-- Вывод значения из свойства модели: --}}
                                                @else
                                                    <td class="align-middle text-center">{{ $qrProfile->$currentFieldName }}</td>
                                                @endif
                                            @endforeach

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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @include('components.filter-script', ['idFilterForm' => 'qrs_filter_form'])
@endpush
