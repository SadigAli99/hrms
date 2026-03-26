<!DOCTYPE html>
<html lang="az">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR AI Platform - @yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('assets/js/tailwind-config.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/hr-platform.css') }}">
    @stack('css')
</head>

<body data-page="dashboard">
    @include('includes.sidebar')
    <div class="main-content page-shell">
        @include('includes.header')

        <div class="content-wrap">
            @yield('content')
        </div>
    </div>
    <script src="{{ asset('assets/js/hr-platform.js') }}"></script>
    <script src="{{ asset('assets/js/ui-shell.js') }}"></script>
    @stack('js')
</body>

</html>
