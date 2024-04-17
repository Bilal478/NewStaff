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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ url(mix('css/app.css')) }}">
        @livewireStyles

        <script src="{{ url(mix('js/app.js')) }}" defer></script>
    </head>

    <body class="bg-gray-50">
        <div class="flex flex-col justify-center min-h-screen py-12 bg-gray-50 px-6 lg:px-8">
		@if(isset($slot))
		   {{ $slot }}
		@else
			@yield('content')
		@endif
        </div>

        @livewireScripts
    </body>
</html>
