<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) && $title ? $title . ' | J&J GROUP' : 'J&J GROUP — Authorized Portal Access' }}</title>
        <link rel="icon" type="image/png" href="{{ asset('img/logo-jnj.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-inter text-slate-900 antialiased">
        <div class="min-h-screen bg-white">
            {{ $slot }}
        </div>
        @livewireScripts
    </body>
</html>
