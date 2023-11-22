@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Клиенты'])

    <div class="container-fluid py-4">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="">Список клиентов</h6>
                            </div>
                            <div class="col-6">
                                <a class="btn btn-success" href="{{ route('clients.create') }}">Добавить клиента</a>
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
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Дата рождения</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Статус</th>
                                        <th class="text-center text-secondary text-xs font-weight-bolder opacity-7">Действие</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clients as $client)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $client->lastname }} {{ $client->firstname }} {{ $client->surename }}</h6>
                                            </div>
                                            <td class="align-middle text-center">{{ $client->email }}</td>
                                            <td class="align-middle text-center">{{ $client->phone_number }}</td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $client->bdate }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-success">{{ $client->id_status }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST">
                                                    <a class="btn btn-info" href="{{ route('clients.show', $client->id) }}">Просмотр</a>
                                                    <a class="btn btn-primary" href="{{ route('clients.edit', $client->id) }}">Редактировать</a>

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger">Удалить</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {!! $clients->links() !!}
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
