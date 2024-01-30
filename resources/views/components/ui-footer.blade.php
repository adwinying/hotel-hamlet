<footer class="w-full bg-slate-800 text-gray-50">
  <div class="container mx-auto px-3 flex flex-col md:flex-row items-start justify-between">
    <!-- Logo -->
    <div class="px-4 py-2 md:px-6 md:py-4 inline-block bg-white font-display text-lg text-black md:text-2xl text-center rounded-b shadow">
      <a href="/">
        <img src="/img/logo.svg" alt="Hotel Hamlet Logo" class="h-16 md:h-20 w-auto mb-2">
        <p class="leading-tight">Hotel<br>Hamlet</p>
      </a>
    </div>

    <!-- Hotel List -->
    <div class="py-10 space-y-1">
      <a class="block text-2xl font-bold text-white hover:text-gray-100 mb-2" href="/hotels">Our Hotels</a>
      <a class="block leading-tight text-gray-300 hover:text-gray-400 transition-colors" href="/hotels/city">Hotel Hamlet City</a>
      <a class="block leading-tight text-gray-300 hover:text-gray-400 transition-colors" href="/hotels/beachside">Hotel Hamlet Beachside</a>
      <a class="block leading-tight text-gray-300 hover:text-gray-400 transition-colors" href="/hotels/ski">Hotel Hamlet Ski Resort</a>
      <a class="block leading-tight text-gray-300 hover:text-gray-400 transition-colors" href="/hotels/campgrounds">Hotel Hamlet Campgrounds</a>
      <a class="block leading-tight text-gray-300 hover:text-gray-400 transition-colors" href="/hotels/airport">Hotel Hamlet Airport Express</a>
    </div>

    <!-- Sitemap -->
    <div class="py-10 space-y-2">
      <a class="block text-2xl font-bold text-white hover:text-gray-100" href="/home">Home</a>
      <a class="block text-2xl font-bold text-white hover:text-gray-100" href="/about">About Us</a>
      <a class="block text-2xl font-bold text-white hover:text-gray-100" href="/news">News</a>
      <a class="block text-2xl font-bold text-white hover:text-gray-100" href="/reservation">Book A Room</a>
      <a class="block text-2xl font-bold text-white hover:text-gray-100" href="/contact">Contact Us</a>
    </div>

    <!-- Admin -->
    <div class="py-10 space-y-2">
      <a class="block text-2xl font-bold text-white hover:text-gray-100" href="/admin">Admin Login</a>
      <a class="block text-2xl font-bold text-white hover:text-gray-100" href="//github.com/adwinying/hotel-hamlet" target="_blank">Github</a>
    </div>
  </div>

  <div class="container mx-auto px-3 py-10">
    &copy; {{ date('Y') }} Hotel Hamlet. All rights reserved.
  </div>
</footer>
