@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Клиенты'])

    <div class="container-fluid py-4">
        <div>
            @include('components.alert')
        </div>

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
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">№</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7 ps-2">ФИО</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Email</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Моб. номер</th>
{{--                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Дата рождения</th>--}}
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Статус</th>
{{--                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Комментарий менеджера</th>--}}
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clients as $client)
                                        <tr>
                                            <td class="align-middle text-center">{{ $client->id }}</td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $client->lastname }} {{ $client->firstname }} {{ $client->surname }}</h6>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">{{ $client->email }}</td>
                                            <td class="align-middle text-center">{{ $client->phone_number }}</td>
{{--                                            <td class="align-middle text-center">--}}
{{--                                                <span class="text-secondary text-xs font-weight-bold">{{ $client->bdate }}</span>--}}
{{--                                            </td>--}}
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-success" style="width: 100px; padding-top: 0.74rem; padding-bottom: 0.74rem;">{{ $client->getStatusName(intval($client->id_status)) }}</span>
                                            </td>
{{--                                            <td class="align-middle text-center text-sm">--}}
{{--                                                {{ strip_tags($client->manager_comment) }}--}}
{{--                                            </td>--}}
                                            <td class="align-middle">
                                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST">
                                                    <a class="btn btn-info btn-sm" style="margin-bottom: 0;" href="{{ route('clients.show', $client->id) }}"><i class="fas fa-eye"></i></a>
                                                    <a class="btn btn-primary btn-sm" style="margin-bottom: 0;" href="{{ route('clients.edit', $client->id) }}"><i class="fas fa-edit"></i></a>

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm" style="margin-bottom: 0;"><i class="fas fa-trash"></i></button>
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
{{--    <script src="./assets/js/plugins/chartjs.min.js"></script>--}}
    <script>

    </script>
@endpush
