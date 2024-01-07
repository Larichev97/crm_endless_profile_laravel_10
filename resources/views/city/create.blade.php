@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Добавление города'])

    <div class="container-fluid py-4 mt-4">
        <form class="card p-4" action="{{ route('cities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <a style="width: fit-content;" class="btn btn-dark" href="{{ route('cities.index') }}">Назад</a>

            <div>
                @include('components.alert')
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Название <span style="color: red">*</span></strong>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Укажите название города..." value="{{ old('name') }}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Страна <span style="color: red">*</span></strong>
                        <br>
                        <select class="form-control @error('id_country') is-invalid @enderror" name="id_country" id="id_country">
                            <option value="0" {{ old('id_country') == '0' ? 'selected' : '' }}>Выберите из списка...</option>
                            {{-- Массив коллекций стран только с полями "id" и "name" --}}
                            @if(!empty($countriesListData))
                                @foreach($countriesListData as $countryItem)
                                    <option value="{{ $countryItem->id }}" {{ old('id_country') == $countryItem->id ? 'selected' : '' }}>
                                        {{ $countryItem->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Включен</strong>
                        <div class="form-check">
                            <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" name="is_active" value="1" id="is_active" @if((int) (old('is_active') == 1)) checked="" @endif>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="submit" class="btn btn-success btn-lg w-100">Добавить</button>
                </div>
            </div>

        </form>

        @include('layouts.footers.auth.footer')
    </div>
@endsection
