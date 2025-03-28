<!DOCTYPE html>
<html lang="uk">

<head>
    @if(isset($showGoogleTag) && $showGoogleTag)
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=AW-16950478678"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'AW-16950478678');
        </script>
    @endif
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/img/favicon.png">
    <title>@auth CRM Endless Profile @else Endless Profile @endauth</title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="/assets/css/argon-dashboard.css" rel="stylesheet" />
</head>

<body class="{{ $class ?? '' }}">
    @guest
        <div class="min-height-130 position-absolute w-100" style="background-color: #172b4d !important;"></div>
        @yield('content')
    @endguest

    @auth
        @if (in_array(request()->route()->getName(), ['sign-in-static', 'sign-up-static', 'admin.login', 'admin.register', 'admin.recover-password', 'rtl', 'virtual-reality']))
            @yield('content')
        @else
            @if (!in_array(request()->route()->getName(), ['profile', 'profile-static']))
                <div class="min-height-130 position-absolute w-100" style="background-color: #172b4d !important;"></div>
            @elseif (in_array(request()->route()->getName(), ['profile-static', 'profile']))
                <div class="position-absolute w-100 min-height-300 top-0" style="background-image: url('/img/profile-layout-header.jpg'); background-position-y: 50%;">
                    <span class="mask bg-dark opacity-6"></span>
                </div>
            @endif

            @if(request()->route()->getName() != 'front-qrs.show')
                @include('layouts.navbars.auth.sidenav')
            @endif
            <main class="main-content border-radius-lg">
                @yield('content')
            </main>
            {{--
            @include('components.fixed-plugin')
            --}}
        @endif
    @endauth

    <!--   Core JS Files   -->
    <script src="/assets/js/core/jquery-3.7.1.js"></script>
    <script src="/assets/js/core/jquery.inputmask.bundle.js"></script>
    <script src="/assets/js/core/popper.min.js"></script>
    <script src="/assets/js/core/bootstrap.min.js"></script>
    <script src="/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        /* Использование jQuery через "$": */
        window.$ = window.jQuery = jQuery;
        /* ------------------------------- */

        var win = navigator.platform.indexOf('Win') > -1;

        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    @auth
        <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="/assets/js/argon-dashboard.js"></script>
    @endauth
    @stack('js')
</body>

</html>
