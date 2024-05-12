<x-layout>
  <div class="hero min-h-screen bg-base-200">
    <div class="hero-content text-center">
      <div class="max-w-md">
        <h1 class="text-5xl font-bold mb-10">Hello call result against server in</h1>
        <h1 class="text-6xl font-bold mb-10 uppercase">
          {{ $lang }}
        </h1>
        <p class="py-5">{{ $message }}</p>
        <p class="prose">Elased time: {{ $elapsed }}ms</p>
      </div>
    </div>
  </div>
</x-layout>