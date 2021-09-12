<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <link rel="manifest" href="/site.webmanifest">
  <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#000000">
  <meta name="apple-mobile-web-app-title" content="Hotel Hamlet">
  <meta name="application-name" content="Hotel Hamlet">
  <meta name="msapplication-TileColor" content="#00aba9">
  <meta name="theme-color" content="#ffffff">

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Gabriela&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
  <title>Hotel Hamlet Admin Dashboard</title>
</head>
<body>
  <div
    class="min-h-screen flex items-center justify-center bg-gray-50
      py-12 lg:px-8">
    <div
      class="container flex flex-col max-w-md p-6 mx-auto rounded-md
        sm:p-8 bg-white text-gray-800 space-y-6 sm:shadow-md">
      <div>
        <img
          class="mx-auto h-24 w-auto"
          src="{{ vite_asset('/img/logo.svg') }}"
          alt="Hotel Hamlet Logo">
        <h2 class="mt-6 text-center text-3xl font-display text-gray-900">
          Hotel Hamlet Admin Login
        </h2>
      </div>

      <form
        class="space-y-6"
        method="POST"
        action="{{ route('login') }}">
        @csrf
        <div class="space-y-4">
          <div>
            <label
              for="email"
              class="block mb-2 text-sm">Email address</label>
            <input
              id="email"
              value="admin@example.com"
              type="email"
              name="email"
              class="w-full px-3 py-2 border rounded-md border-gray-300
              bg-gray-50 text-gray-800
              focus:outline-none focus:ring-cyan-500 focus:border-cyan-600">
          </div>
          <div>
            <label
              for="password"
              class="block mb-2 text-sm">Password</label>
            <input
              id="password"
              value="password"
              type="password"
              name="password"
              class="w-full px-3 py-2 border rounded-md border-gray-300
              bg-gray-50 text-gray-800
              focus:outline-none focus:ring-cyan-500 focus:border-cyan-600">
          </div>
        </div>

        <input
          type="hidden"
          name="remember"
          value="0">

        @error('email', 'password', 'remember')
          <div class="text-red-500 text-center">
            Invalid credentials.
          </div>
        @enderror

        <button
          type="submit"
          class="w-full px-8 py-3 rounded-md bg-cyan-600
          hover:bg-cyan-800 text-gray-50
          focus:outline-none focus:ring-4 focus:ring-cyan-500 focus:ring-opacity-60">
          Sign in
        </button>
      </form>
    </div>
  </div>

  @if(app()->environment('local'))
    <script>window.global = window;</script>
  @endif
  @vite
</body>
</html>
