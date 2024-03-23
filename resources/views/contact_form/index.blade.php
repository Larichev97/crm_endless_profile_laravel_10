@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Заявки из формы обратной связи'])

    <div class="container-fluid py-4">
        @include('components.alert')

        @php $isObjectFilterFiles = (isset($filterFieldsObject) && is_object($filterFieldsObject)); @endphp

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="">Список заявок</h5>
                            </div>
                            <div class="col-6 d-flex">
                            {{-- <a class="btn btn-success" href="{{ route('contact-forms.create') }}" style="margin-left: auto">Добавить заявку</a> --}}
                                <a class="btn btn-danger ms-auto" href="{{ route('contact-forms.index') }}" style="margin-left: 15px;">Очистить фильтр</a>
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
                                                    <a href="{{ route('contact-forms.index', ['sort_by' => $displayedFieldArray['field'], 'sort_way' => 'desc']) }}"><i class="fa fa-arrow-down @if($sortBy === $displayedFieldArray['field'] && $sortWay === 'desc') text-warning @endif"></i></a>
                                                    <a href="{{ route('contact-forms.index', ['sort_by' => $displayedFieldArray['field'], 'sort_way' => 'asc']) }}"><i class="fa fa-arrow-up @if($sortBy === $displayedFieldArray['field'] && $sortWay === 'asc') text-warning @endif"></i></a>
                                                </th>
                                            @endforeach

                                            <th class="align-middle text-center text-secondary text-xs font-weight-bolder opacity-8">Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <form id="contacts_filter_form" action="{{ route('contact-forms.index') }}" method="GET">
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

                                                                    @if('id_status' === $currentFieldName && !empty($statusesListData))
                                                                        @foreach($statusesListData as $statusItem)
                                                                            <option value="{{ $statusItem['id'] }}" @if($isObjectFilterFiles && !empty($filterFieldsObject->$currentFieldName) && (int) $filterFieldsObject->$currentFieldName == (int) $statusItem['id']) selected="selected" @endif>
                                                                                {{ $statusItem['name'] }}
                                                                            </option>
                                                                        @endforeach
                                                                    @elseif('id_employee' === $currentFieldName && !empty($employeesListData))
                                                                        @foreach($employeesListData as $employeeItem)
                                                                            <option value="{{ $employeeItem->id }}" @if($isObjectFilterFiles && !empty($filterFieldsObject->$currentFieldName) && (int) $filterFieldsObject->$currentFieldName == (int) $employeeItem->id) selected="selected" @endif>
                                                                                {{ $employeeItem->name }}
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
                                        @foreach ($contactForms as $contactFormItem)
                                            <tr>
                                                @foreach($displayedFields as $displayedFieldArray)
                                                    @php $currentFieldName = (string) $displayedFieldArray['field']; @endphp

                                                    {{-- Уникальный вывод значения у поля: --}}
                                                    @if('id_status' === $currentFieldName)
                                                        <td class="align-middle text-center text-sm">
                                                            <span class="badge badge-sm bg-gradient-{{ $contactFormItem->statusGradientColor }}" style="width: 100px; padding-top: 0.74rem; padding-bottom: 0.74rem;">{{ $contactFormItem->statusName }}</span>
                                                        </td>
                                                    @elseif('id_employee' === $currentFieldName)
                                                        <td class="align-middle text-center">{{ $contactFormItem->userWhoUpdated->fullName ?? '--' }}</td>
                                                    {{-- Вывод значения из свойства модели: --}}
                                                    @else
                                                        <td class="align-middle text-center">{{ $contactFormItem->$currentFieldName }}</td>
                                                    @endif
                                                @endforeach

                                                <td class="align-middle text-center">
                                                    <form action="{{ route('contact-forms.destroy', $contactFormItem->id) }}" method="POST">
                                                        <a class="btn btn-info btn-sm" href="{{ route('contact-forms.show', $contactFormItem->id) }}" style="margin-bottom: 0; padding-left: 12px; padding-right: 12px;"><i class="fas fa-eye"></i></a>
                                                        <a class="btn btn-primary btn-sm" href="{{ route('contact-forms.edit', $contactFormItem->id) }}" style="margin-bottom: 0; padding-left: 12px; padding-right: 12px;"><i class="fas fa-edit"></i></a>

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
                                {!! $contactForms->links('components.pagination') !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @include('components.filter-script', ['idFilterForm' => 'contacts_filter_form'])
@endpush
