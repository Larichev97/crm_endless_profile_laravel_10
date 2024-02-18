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
                                <h6 class="">Список заявок</h6>
                            </div>
                            <div class="col-6 d-flex">
                            {{-- <a class="btn btn-success" href="{{ route('contact-forms.create') }}" style="margin-left: auto">Добавить заявку</a> --}}
                                <a class="btn btn-danger ms-auto" href="{{ route('contact-forms.index') }}" style="margin-left: 15px;">Очистить фильтр</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="align-middle text-center text-secondary text-xs font-weight-bolder opacity-7">#</th>
                                        <th class="align-middle text-center text-secondary text-xs font-weight-bolder opacity-7">Имя</th>
                                        <th class="align-middle text-center text-secondary text-xs font-weight-bolder opacity-7">Фамилия</th>
                                        <th class="align-middle text-center text-secondary text-xs font-weight-bolder opacity-7">Email</th>
                                        <th class="align-middle text-center text-secondary text-xs font-weight-bolder opacity-7">Номер телефона</th>
                                        <th class="align-middle text-center text-secondary text-xs font-weight-bolder opacity-7">Статус заявки</th>
                                        <th class="align-middle text-center text-secondary text-xs font-weight-bolder opacity-7">Кто редактировал</th>
                                        <th class="align-middle text-center text-secondary text-xs font-weight-bolder opacity-7">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <form id="contacts_filter_form" action="{{ route('contact-forms.index') }}" method="GET">
                                            <th class="text-center font-weight-bolder">
                                                <input type="text" name="filter_id" id="filter_id" class="form-control" value="@if($isObjectFilterFiles && !empty($filterFieldsObject->id)){{ $filterFieldsObject->id }}@endif">
                                            </th>
                                            <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">
                                                <input type="text" name="filter_firstname" id="filter_firstname" class="form-control" value="@if($isObjectFilterFiles && !empty($filterFieldsObject->firstname)){{ $filterFieldsObject->firstname }}@endif">
                                            </th>
                                            <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">
                                                <input type="text" name="filter_lastname" id="filter_lastname" class="form-control" value="@if($isObjectFilterFiles && !empty($filterFieldsObject->lastname)){{ $filterFieldsObject->lastname }}@endif">
                                            </th>
                                            <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">
                                                <input type="text" name="filter_email" id="filter_email" class="form-control" value="@if($isObjectFilterFiles && !empty($filterFieldsObject->email)){{ $filterFieldsObject->email }}@endif">
                                            </th>
                                            <th class="text-center font-weight-bolder">
                                                <input type="text" name="filter_phone_number" id="filter_phone_number" class="form-control" value="@if($isObjectFilterFiles && !empty($filterFieldsObject->phone_number)){{ $filterFieldsObject->phone_number }}@endif">
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
                                            <th class="text-center font-weight-bolder">
                                                <select class="form-control" name="filter_id_employee" id="filter_id_employee">
                                                    <option value="0" @if($isObjectFilterFiles && empty($filterFieldsObject->id_employee)) selected="selected" @endif>Выберите сотрудника из списка...</option>
                                                    {{-- Массив коллекций сотрудников только с полями "id" и "name" --}}
                                                    @if(!empty($employeesListData))
                                                        @foreach($employeesListData as $employeeItem)
                                                            <option value="{{ $employeeItem->id }}" @if($isObjectFilterFiles && !empty($filterFieldsObject->id_employee) && (int) $filterFieldsObject->id_employee == (int) $employeeItem->id) selected="selected" @endif>
                                                                {{ $employeeItem->name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </th>
                                            <th class="text-center text-secondary font-weight-bolder">
                                                <button type="submit" class="btn btn-info" style="margin-bottom: 0;">Фильтр</button>
                                            </th>
                                        </form>
                                    </tr>
                                    @foreach ($contactForms as $contactFormItem)
                                        <tr>
                                            <td class="align-middle text-center">{{ $contactFormItem->id }}</td>
                                            <td class="align-middle text-center">{{ $contactFormItem->firstname }}</td>
                                            <td class="align-middle text-center">{{ $contactFormItem->lastname }}</td>
                                            <td class="align-middle text-center">{{ $contactFormItem->email }}</td>
                                            <td class="align-middle text-center">{{ $contactFormItem->phone_number }}</td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-{{ $contactFormItem->statusGradientColor }}" style="width: 100px; padding-top: 0.74rem; padding-bottom: 0.74rem;">{{ $contactFormItem->statusName }}</span>
                                            </td>
                                            <td class="align-middle text-center">{{ $contactFormItem->userWhoUpdated->fullName ?? '--' }}</td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @include('components.filter-script', ['idFilterForm' => 'contacts_filter_form'])
@endpush
