<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('titulo')@yield('titulo') | @endif{{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="@yield('classe_body')">
    @yield('corpo')

    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
