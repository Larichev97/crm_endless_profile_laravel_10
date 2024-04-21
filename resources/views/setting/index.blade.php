@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Настройки'])

    <div class="container-fluid py-4">
        @include('components.alert')

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="">Список настроек для кода</h5>
                            </div>
                            <div class="col-6 d-flex">
                                <a class="btn btn-success" href="{{ route('admin.settings.create') }}" style="margin-left: auto">Добавить настройку</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder opacity-7">#</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Название</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Значение</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($settings as $settingItem)
                                    <tr>
                                        <td class="align-middle text-center">{{ $settingItem->id }}</td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $settingItem->name }}</h6>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $settingItem->value }}</h6>
                                            </div>
                                        </td>

                                        @include('components.table-with-data.table-row-actions', ['entityId' => $settingItem->id, 'destroyRouteName' => 'admin.settings.destroy', 'showRouteName' => 'admin.settings.show', 'editRouteName' => 'admin.settings.edit',])
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{-- Custom pagination template: resources/views/components/pagination.blade.php --}}
                            {!! $settings->links('components.pagination') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
