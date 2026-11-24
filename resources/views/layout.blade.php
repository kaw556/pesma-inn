<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin · Pesma Inn</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-200/50 font-sans text-base-content antialiased">

  <header class="sticky top-0 z-30 bg-primary text-primary-content shadow-sm">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-5 py-4 lg:px-8">
      <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
        <img src="{{ asset('img/logo-resmi-pesma.png') }}" alt="Logo Pesma" class="footer-logo h-10 w-10 object-contain">
        <span class="leading-tight">
          <span class="block text-base font-semibold">Pesma Inn</span>
          <span class="block text-[10px] uppercase tracking-[0.18em] text-primary-content/60">Admin Panel · KH. Mas Mansur</span>
        </span>
      </a>

      <nav class="order-3 flex w-full flex-wrap items-center gap-x-5 gap-y-2 border-t border-white/10 pt-3 md:order-none md:w-auto md:border-0 md:pt-0" aria-label="Navigasi admin">
        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-primary-content/75 transition hover:text-white">Dashboard</a>
        <a href="{{ route('kamar.index') }}" class="text-sm font-medium text-primary-content/75 transition hover:text-white">Data Kamar</a>
        <a href="{{ route('pengunjung.index') }}" class="text-sm font-medium text-primary-content/75 transition hover:text-white">Pengunjung</a>
        <a href="{{ route('pembayaran.konfirmasi') }}" class="text-sm font-medium text-primary-content/75 transition hover:text-white">Pembayaran</a>
        <a href="{{ route('report.monthly') }}" class="text-sm font-medium text-primary-content/75 transition hover:text-white">Report</a>
        <a href="{{ route('beranda.edit') }}" class="text-sm font-medium text-primary-content/75 transition hover:text-white">Edit Beranda</a>
      </nav>

      <form method="POST" action="{{ route('auth.logout') }}">
        @csrf
        <button type="submit" class="btn btn-ghost btn-sm rounded-full border border-white/30 font-medium normal-case text-primary-content hover:bg-white/10">
          <i class="fas fa-right-from-bracket text-xs"></i> Logout
        </button>
      </form>
    </div>
  </header>

  <main class="min-h-[70vh]">
    <div class="mx-auto max-w-7xl px-5 py-8 lg:px-8 lg:py-10">
      @yield('content')
    </div>
  </main>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      });
    @endif

    @if(session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true
      });
    @endif

    @if($errors->any())
      Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        html: '<ul style="text-align:left;margin:0;padding-left:20px">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true
      });
    @endif
  });
  </script>

</body>
</html>
