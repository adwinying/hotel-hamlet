<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Hotel Hamlet' }}</title>
    @vite('resources/css/app.css')
  </head>
  <body class="bg-gray-50">
    @yield('content')
    @livewireScripts
  </body>
</html>
