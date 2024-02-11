@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Добавление фотографий для галереи QR-профиля #' . $idQrProfile])

    <div class="container-fluid py-4 mt-5">
        <form class="card p-4" action="{{ route('qrs.store-gallery-images') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

            <a style="width: fit-content;" class="btn btn-dark" href="{{ route('qrs.show', $idQrProfile) }}">Назад</a>

            <div>
                @include('components.alert')
            </div>

            <div class="row">
                <input type="hidden" name="id_qr_profile" class="form-control" value="{{ $idQrProfile }}">
                <input type="hidden" name="is_active" class="form-control" value="1">

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Фотографии (можно добавлять несколько)</strong>
                        <input type="file" name="gallery_photos[]" class="form-control @error('gallery_photos') is-invalid @enderror mt-4" id="gallery_photos" multiple>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg w-100">Добавить</button>
                </div>
            </div>

        </form>

        @include('layouts.footers.auth.footer')
    </div>
@endsection
