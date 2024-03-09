@if(!empty($idGallery) && !empty($galleryData) && is_array($galleryData))
    <div class="card card-carousel overflow-hidden h-100 p-0">
        <div id="{{ $idGallery }}" class="carousel slide h-100" data-bs-ride="carousel">
            <div class="carousel-inner border-radius-lg h-100">
                @foreach($galleryData as $keyItem => $galleryItem)
                    @if(!empty($galleryItem['imagePath']))
                        <div class="carousel-item{{ $keyItem == 0 ? ' active' : '' }} h-100">
                            <img src="{{ asset($galleryItem['imagePath']) }}" class="d-block w-100" alt="{{ $galleryItem['imageAlt'] }}" style="max-width: 100%; display: block; margin: 0 auto; height: {{ (int) $imageHeight }}px;">
                        </div>
                    @endif
                @endforeach
            </div>
            <button class="carousel-control-prev w-5 me-3" type="button" data-bs-target="#{{ $idGallery }}" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Назад</span>
            </button>
            <button class="carousel-control-next w-5 me-3" type="button" data-bs-target="#{{ $idGallery }}" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Далее</span>
            </button>
        </div>
    </div>












{{--    <div class="card card-carousel overflow-hidden h-100 p-0">--}}
{{--        <div id="{{ $idGallery }}" class="carousel slide h-100" data-bs-ride="carousel">--}}
{{--            <div class="carousel-inner border-radius-lg h-100">--}}
{{--                @foreach($galleryData as $keyItem => $galleryItem)--}}
{{--                    @if(!empty($galleryItem['imagePath']))--}}
{{--                        <div class="carousel-item h-100 {{ $keyItem == 0 ? ' active ' : '' }}" style="background-image: url( {{ asset($galleryItem['imagePath']) }}); background-size: cover;">--}}
{{--                            <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">--}}
{{--    --}}{{--                            <img src="{{ asset($galleryItem['imagePath']) }}" alt="test">--}}

{{--                                @if(!empty($galleryItem['icon']))--}}
{{--                                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">--}}
{{--                                        <i class="ni ni-camera-compact text-dark opacity-10"></i>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                                @if(!empty($galleryItem['headerText']))--}}
{{--                                    <h5 class="text-white mb-1">{{ $galleryItem['headerText'] }}</h5>--}}
{{--                                @endif--}}
{{--                                @if(!empty($galleryItem['bodyText']))--}}
{{--                                    <p>{{ $galleryItem['bodyText'] }}</p>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    @endif--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--            <button class="carousel-control-prev w-5 me-3" type="button"--}}
{{--                    data-bs-target="#{{ $idGallery }}" data-bs-slide="prev">--}}
{{--                <span class="carousel-control-prev-icon" aria-hidden="true"></span>--}}
{{--                <span class="visually-hidden">Назад</span>--}}
{{--            </button>--}}
{{--            <button class="carousel-control-next w-5 me-3" type="button"--}}
{{--                    data-bs-target="#{{ $idGallery }}" data-bs-slide="next">--}}
{{--                <span class="carousel-control-next-icon" aria-hidden="true"></span>--}}
{{--                <span class="visually-hidden">Далее</span>--}}
{{--            </button>--}}
{{--        </div>--}}
{{--    </div>--}}
@endif
