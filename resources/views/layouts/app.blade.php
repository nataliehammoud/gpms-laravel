<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- المكتبات المحلية (CSS فقط) -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/solid.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/brands.min.css') }}">

    <!-- Vite -->
    @vite(['resources/js/app.js'])

    <style>
        /* خط كايرو محلي */
        @font-face {
            font-family: 'Cairo';
            src: url("{{ asset('fonts/Cairo-Regular.ttf') }}") format('truetype');
            font-weight: 400;
        }
        @font-face {
            font-family: 'Cairo';
            src: url("{{ asset('fonts/Cairo-Bold.ttf') }}") format('truetype');
            font-weight: 700;
        }

        /* أيقونات Font Awesome محلية */
        @font-face {
            font-family: 'Font Awesome 6 Free';
            src: url("{{ asset('fonts/fa-solid-900.woff2') }}") format('woff2');
            font-weight: 900;
        }
        @font-face {
            font-family: 'Font Awesome 6 Brands';
            src: url("{{ asset('fonts/fa-brands-400.woff2') }}") format('woff2');
            font-weight: 400;
        }

        * { font-family: 'Cairo', sans-serif !important; }

        .fa, .fas, .fa-solid {
            font-family: 'Font Awesome 6 Free' !important;
            font-weight: 900 !important;
        }
        .fab, .fa-brands {
            font-family: 'Font Awesome 6 Brands' !important;
            font-weight: 400 !important;
        }
    </style>

    <title>GPMS</title>
</head>
<body class="antialiased bg-light">
    {{ $slot }}

    <!-- Bootstrap JS (مرة واحدة فقط وفي نهاية الصفحة) -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
