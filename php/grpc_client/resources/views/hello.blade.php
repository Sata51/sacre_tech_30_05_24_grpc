<!DOCTYPE html>
<html data-theme="coffee">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <link rel="stylesheet" type="text/css" href="{{ asset('css/daisyui.min.css') }}">
  <script src="{{ asset('js/tailwind.min.js') }}"></script> -->

  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.11.1/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
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
</body>

</html>