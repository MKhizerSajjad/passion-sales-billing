<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        {{-- <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}

        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('admin/images/favicon.ico') }}" />
        <link rel="stylesheet" href="{{ asset('admin/css/backend-plugin.min28b5.css?v=2.0.0') }}">
        <link rel="stylesheet" href="{{ asset('admin/css/backend28b5.css?v=2.0.0') }}">
        <link rel="stylesheet" href="{{ asset('admin/vendor/fortawesome/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/vendor/remixicon/fonts/remixicon.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/vendor/icon/dripicons/dripicons.css') }}">

        <link rel='stylesheet' href="{{ asset('admin/vendor/fullcalendar/core/main.css') }}"/>
        <link rel='stylesheet' href="{{ asset('admin/vendor/fullcalendar/daygrid/main.css') }}"/>
        <link rel='stylesheet' href="{{ asset('admin/vendor/fullcalendar/timegrid/main.css') }}"/>
        <link rel='stylesheet' href="{{ asset('admin/vendor/fullcalendar/list/main.css') }}"/>
        <link rel="stylesheet" href="{{ asset('admin/vendor/mapbox/mapbox-gl.css') }}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            @include('admin.common.footer')
        </div>

        <!-- Backend Bundle JavaScript -->
        <script src="{{ asset('admin/js/backend-bundle.min.js') }}"></script>

        <!-- Flextree Javascript-->
        <script src="{{ asset('admin/js/flex-tree.min.js') }}"></script>
        <script src="{{ asset('admin/js/tree.js') }}"></script>

        <!-- Table Treeview JavaScript -->
        <script src="{{ asset('admin/js/table-treeview.js') }}"></script>

        <!-- Masonary Gallery Javascript -->
        <script src="{{ asset('admin/js/masonry.pkgd.min.js') }}"></script>
        <script src="{{ asset('admin/js/imagesloaded.pkgd.min.js') }}"></script>

        <!-- Mapbox Javascript -->
        <script src="{{ asset('admin/js/mapbox-gl.js') }}"></script>
        <script src="{{ asset('admin/js/mapbox.js') }}"></script>

        <!-- Fullcalender Javascript -->
        <script src='{{ asset('admin/vendor/fullcalendar/core/main.js') }}'></script>
        <script src='{{ asset('admin/vendor/fullcalendar/daygrid/main.js') }}'></script>
        <script src='{{ asset('admin/vendor/fullcalendar/timegrid/main.js') }}'></script>
        <script src='{{ asset('admin/vendor/fullcalendar/list/main.js') }}'></script>

        <!-- SweetAlert JavaScript -->
        <script src="{{ asset('admin/js/sweetalert.js') }}"></script>

        <!-- Vectoe Map JavaScript -->
        <script src="{{ asset('admin/js/vector-map-custom.js') }}"></script>

        <!-- Chart Custom JavaScript -->
        <script src="{{ asset('admin/js/customizer.js') }}"></script>
        <script src="{{ asset('admin/js/rtl.js') }}"></script>

        <!-- Chart Custom JavaScript -->
        <script src="{{ asset('admin/js/chart-custom.js') }}"></script>

        <!-- slider JavaScript -->
        <script src="{{ asset('admin/js/slider.js') }}"></script>

        <!-- app JavaScript -->
        <script src="{{ asset('admin/js/app.js') }}"></script>


        {{-- auto close alerts --}}
        <script>
            setTimeout(function() {
                $(".auto-colse-3").slideUp(500);
            }, 3000); // after 3secs
            setTimeout(function() {
                $(".auto-colse-10").slideUp(500);
            }, 10000); // after 10secs
        </script>
    </body>
</html>
