<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @isset ($title)
            <title>{{ $title }} - {{ config('app.name') }}</title>
        @else
            <title>{{ config('app.name') }}</title>
        @endif

	<link rel="shortcut icon" href="{{ url(asset('neostaff-icon.png')) }}">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ url(mix('css/app.css')) }}">
        @livewireStyles

        <script src="{{ url(mix('js/app.js')) }}" defer></script>
        @stack('style')

    </head>

    <body class="bg-gray-50 relative flex flex-col">
        <x-navigation.sidebar-admin />
        {{--
        <x-navigation.mobile />
        --}}

        <main class="relative flex-1 px-5 py-10 overflow-y-scroll md:px-10 lg:ml-60">
            {{ $slot }}
        </main>

        @stack('modals')
        <x-notifications.toast />

        @livewireScripts
        @stack('scripts')
        <script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js" data-turbolinks-eval="false" data-turbo-eval="false"></script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.select2-single').select2();
            })
        </script>
    </body>
</html>
