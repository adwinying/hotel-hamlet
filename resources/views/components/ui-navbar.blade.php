<navbar class="block bg-white h-14 drop-shadow" x-data="{ open: false }">
  <div class="container mx-auto px-3 md:flex justify-between">
    <!-- Logo -->
    <div class="px-4 py-2 md:px-6 md:py-4 inline-block bg-white font-display text-lg md:text-2xl text-center rounded">
      <a href="/">
        <img src="/img/logo.svg" alt="Hotel Hamlet Logo" class="h-16 md:h-20 w-auto mb-2">
        <p class="leading-tight">Hotel<br>Hamlet</p>
      </a>
    </div>

    <!-- Menu button -->
    <div class="absolute right-0 top-0 md:hidden flex items-center">
      <button class="text-gray-700 text-4xl px-4 py-2.5 leading-none" @click="open = true">
        &equiv;
      </button>
    </div>

    <!-- Menu overlay bg -->
    <div class="md:hidden fixed inset-0 h-screen bg-black/80 z-40" x-show="open" x-transition @click="open = false"></div>

    <!-- Menu list -->
    <div class="fixed top-0 bottom-0 right-0 translate-x-72 md:translate-x-0 w-72 h-screen z-50 p-6 bg-white shadow md:static md:w-auto md:h-14 flex flex-col md:flex-row gap-x-8 gap-y-4 md:items-center md:shadow-none" x-bind:class="open ? 'translate-x-0 transition-all' : 'transition-all'">
      <button class="md:hidden self-end font-bold text-2xl px-2" @click="open = false">&cross;</button>
      <a class="text-gray-700 hover:text-gray-500 transition-colors font-bold text-lg md:text-md" href="/about">About</a>
      <a class="text-gray-700 hover:text-gray-500 transition-colors font-bold text-lg md:text-md" href="/hotels">Our Hotels</a>
      <a class="text-gray-700 hover:text-gray-500 transition-colors font-bold text-lg md:text-md" href="/news">News</a>
      <x-ui-button component="a" variant="primary" size="lg" size-md="md" href="/reservation">Book Now</x-ui-button>
    </div>
  </div>
</navbar>
