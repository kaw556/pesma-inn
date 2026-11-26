<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" media="(prefers-color-scheme: light)" href="{{ asset('img/logo-resmi-pesma.png') }}">
  <link rel="icon" type="image/png" media="(prefers-color-scheme: dark)" href="{{ asset('img/logo-resmi-pesma-white.png') }}">
  <title>@yield('title', 'Pesan Kamar · Pesma Inn')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200/50 font-sans text-base-content antialiased">

  <header class="bg-primary text-primary-content shadow-sm">
    <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-5 py-4 lg:px-8">
      <a href="{{ url('/') }}" class="flex items-center gap-3">
        <img src="{{ asset('img/logo-resmi-pesma.png') }}" alt="Logo Pesma" class="footer-logo h-10 w-10 object-contain">
        <span class="leading-tight">
          <span class="block text-base font-semibold">Pesma Inn</span>
          <span class="block text-[10px] uppercase tracking-[0.18em] text-primary-content/60">KH. Mas Mansur · Surakarta</span>
        </span>
      </a>
      <a href="{{ url('/') }}" class="btn btn-ghost btn-sm rounded-full border border-white/30 font-medium normal-case text-primary-content hover:bg-white/10">
        <i class="fas fa-house text-xs"></i> Beranda
      </a>
    </div>
  </header>

  <main class="mx-auto max-w-6xl px-5 py-8 lg:px-8 lg:py-10">
    @yield('content')
  </main>

  <script>
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: '{{ session('success') }}',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
      });
    @endif

    @if($errors->any())
      @foreach($errors->all() as $error)
        Swal.fire({
          icon: 'error',
          title: @json($error),
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 5000,
          timerProgressBar: true,
        });
      @endforeach
    @endif
  </script>
</body>
</html>
