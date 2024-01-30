@extends('layouts.client')

@php
$bgImg = $bgImg ?? Arr::random(Storage::disk('public')->allFiles('img/hotels'));
$bgImg = is_array($bgImg) ? Arr::random($bgImg) : $bgImg;
$bgImg = "url('/{$bgImg}')";
@endphp

<header>
  <x-ui-navbar />

  <div class="h-56 md:h-72 flex justify-center items-center bg-slate-300/60 bg-cover bg-center bg-blend-overlay" @style(["background-image: $bgImg"])>
    <h1 class="text-3xl md:text-5xl font-bold">{{ $title }}</h1>
  </div>
</header>

{{ $slot }}

<x-ui-footer />
