@extends('layouts.client')

<header>
  <x-ui-navbar />

  <div class="h-56 md:h-72 flex justify-center items-center bg-gray-300">
    <h1 class="text-3xl md:text-5xl font-bold">{{ $title }}</h1>
  </div>
</header>

{{ $slot }}

<x-ui-footer />
