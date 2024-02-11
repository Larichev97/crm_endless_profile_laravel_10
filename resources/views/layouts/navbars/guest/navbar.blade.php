<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
        <div class="col-12">
            <!-- Navbar -->
            <nav
                class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 start-0 end-0 mx-4">
                <div class="container-fluid">
                    <span class="navbar-brand font-weight-bolder ms-lg-0 ms-3">
                        Endless Profile
                    </span>
                    <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon mt-2">
                            <span class="navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </span>
                    </button>
                    <div class="collapse navbar-collapse" id="navigation">
                        <ul class="navbar-nav mx-auto">
                            <li class="nav-item">
                                <span class="nav-link d-flex align-items-center me-2 active" aria-current="page">
                                    Технології в проєкті:
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link me-2" >
                                    <i class="fab fa-php opacity-6 text-dark me-1"></i>
                                    PHP 8.2
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link d-flex align-items-center me-2 active" aria-current="page">
                                    <i class="fab fa-laravel opacity-6 text-dark me-1"></i>
                                    Laravel 10
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link me-2">
                                    <i class="fas fa-people-carry opacity-6 text-dark me-1"></i>
                                    REST API
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link me-2">
                                    <i class="fas fa-qrcode opacity-6 text-dark me-1"></i>
                                    QR Code
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link me-2">
                                    <i class="fab fa-gitlab opacity-6 text-dark me-1"></i>
                                    GigLab
                                </span>
                            </li>
                            <li class="nav-item">
                                <span class="nav-link me-2">
                                    <i class="fas fa-cogs opacity-6 text-dark me-1"></i>
                                    Design patterns
                                </span>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link me-2 font-weight-bolder" href="{{ route('l5-swagger.default.api') }}">
                                    <i class="fas fa-code opacity-6 text-dark me-1"></i>
                                    Swagger Documentation
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>
    </div>
</div>
