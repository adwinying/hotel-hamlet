<template>
  <header class="w-full bg-cyan-500 pt-20 pb-32 md:pt-12">
    <div class="container mx-auto space-y-4 px-4 md:px-10">
      <slot name="pre-title" />

      <h1 class="text-5xl font-bold">
        <slot />
      </h1>

      <slot name="post-title" />
    </div>
  </header>
</template>
