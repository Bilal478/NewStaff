<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @isset($title)
    <title>{{ $title }} - {{ config('app.name') }}</title>
    @else
    <title>{{ config('app.name') }}</title>
    @endif

    <link rel="shortcut icon" href="{{ url(asset('neostaff-icon.png')) }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ url(mix('css/app.css')) }}">
	<script src="https://js.stripe.com/v3/"></script>
    @livewireStyles

    <script src="{{ url(mix('js/app.js')) }}" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.4/jquery.timepicker.min.css">
    {{-- <link href="https://code.jquery.com/ui/1.10.4/themes/vader/jquery-ui.css" rel="stylesheet"> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @stack('style')
    <style>
        .ui-timepicker-container.ui-timepicker-standard {
            z-index: 1000 !important;
        }
    </style>
</head>

<body class="bg-gray-50 relative flex flex-col">

    <main class="relative flex-1 px-5 py-10 overflow-y-scroll md:px-10 lg:ml-60">
        
    </main>

    
    <x-notifications.toast />


    <script src="https://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="https://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.4/jquery.timepicker.min.js"></script>


    <script>
        $(function() {
                $(".datepicker").datepicker();
            });
    </script>
    
    <script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js"
        data-turbolinks-eval="false" data-turbo-eval="false"></script>


</body>

</html>
