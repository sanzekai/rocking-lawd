<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Rocking Lawd') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-100 antialiased bg-slate-950 selection:bg-red-600 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-8 sm:pt-0 bg-slate-950 px-4">
            <div class="mb-6">
                <a href="/">
                    <x-application-logo />
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-8 bg-slate-900 border border-slate-800 shadow-2xl rounded-xl">
                {{ $slot }}
            </div>

            <div class="mt-8 text-center text-xs text-slate-500">
                <a href="/" class="hover:text-red-500 transition duration-150">&larr; Kembali ke Beranda</a>
            </div>
        </div>
    </body>
</html>
