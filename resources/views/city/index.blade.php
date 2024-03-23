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
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    @include('components.table-with-data.table-filter-header', ['indexRouteName' => 'cities.index'])
                                </thead>
                                <tbody>
                                @foreach ($cities as $cityItem)
                                    <tr>
                                        <td class="align-middle text-center">{{ $cityItem->id }}</td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $cityItem->name }}</h6>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $cityItem->country->name }}</h6>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            @if($cityItem->is_active)
                                                <i class="fas fa-check" style="color: #2dce89"></i>
                                            @else
                                                <i class="fas fa-ban" style="color: #f5365c"></i>
                                            @endif
                                        </td>

                                        @include('components.table-with-data.table-row-actions', ['entityId' => $cityItem->id, 'destroyRouteName' => 'cities.destroy', 'showRouteName' => 'cities.show', 'editRouteName' => 'cities.edit',])
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{-- Custom pagination template: resources/views/components/pagination.blade.php --}}
                            {!! $cities->links('components.pagination') !!}
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
