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
                                <h6 class="">Список настроек</h6>
                            </div>
                            <div class="col-6 d-flex">
                                <a class="btn btn-success" href="{{ route('settings.create') }}" style="margin-left: auto">Добавить настройку</a>
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
                                        <td class="align-middle text-center">
                                            <form action="{{ route('settings.destroy', $settingItem->id) }}" method="POST">
                                                <a class="btn btn-info btn-sm" href="{{ route('settings.show', $settingItem->id) }}" style="margin-bottom: 0; padding-left: 12px; padding-right: 12px;"><i class="fas fa-eye"></i></a>
                                                <a class="btn btn-primary btn-sm" href="{{ route('settings.edit', $settingItem->id) }}" style="margin-bottom: 0; padding-left: 12px; padding-right: 12px;"><i class="fas fa-edit"></i></a>

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
                            {!! $settings->links('components.pagination') !!}
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
