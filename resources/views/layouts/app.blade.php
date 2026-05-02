<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600;700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-100 text-slate-800">
        <div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-amber-50">
            @include('layouts.navigation')

            @isset($header)
                <header class="border-b border-green-100 bg-white/90 shadow-sm backdrop-blur">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        <div class="rounded-2xl border border-green-100 bg-white px-6 py-5 shadow-sm">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endisset

            <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>