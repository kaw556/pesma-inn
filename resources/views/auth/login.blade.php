<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Admin · Pesma Inn</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { brand: '#0d0b61', 'brand-dark': '#080650' },
          fontFamily: { sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
        },
      },
    };
  </script>
</head>
<body class="min-h-screen bg-[#f4f7fb] font-sans text-slate-800 antialiased">
  <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">
    {{-- Dekorasi latar --}}
    <div class="pointer-events-none absolute -left-28 -top-28 h-80 w-80 rounded-full bg-brand/10 blur-3xl" aria-hidden="true"></div>
    <div class="pointer-events-none absolute -bottom-32 -right-24 h-96 w-96 rounded-full bg-[#1fa9b8]/20 blur-3xl" aria-hidden="true"></div>

    <div class="relative grid w-full max-w-5xl overflow-hidden rounded-3xl bg-white shadow-[0_30px_80px_-30px_rgba(13,11,97,0.35)] lg:grid-cols-2">
      {{-- Panel visual --}}
      <aside class="relative hidden flex-col justify-between bg-gradient-to-br from-brand to-brand-dark p-10 text-white lg:flex">
        <div class="flex items-center gap-3">
          <img src="{{ asset('img/logo-pesma.png') }}" alt="Logo Pesma" class="h-11 w-auto">
        </div>

        <div class="my-10">
          <h1 class="text-3xl font-semibold leading-tight">Selamat datang kembali</h1>
          <p class="mt-3 max-w-sm text-sm leading-relaxed text-white/75">
            Masuk untuk mengelola kamar, data pengunjung, dan konfirmasi pembayaran Pesma Inn KH. Mas Mansur.
          </p>
        </div>

        <img src="{{ asset('img/admin-page.png') }}" alt="" class="mx-auto max-h-56 w-auto">
      </aside>

      {{-- Panel form --}}
      <div class="flex flex-col justify-center p-8 sm:p-10">
        <div class="mb-8 flex items-center gap-3 lg:hidden">
          <img src="{{ asset('img/logo-resmi-pesma.png') }}" alt="Logo Pesma" class="h-10 w-10 object-contain">
          <span class="h-8 w-px bg-slate-200"></span>
          <span class="text-left text-sm font-semibold leading-tight text-slate-800">
            Pesma Inn
            <span class="block text-[10px] font-normal uppercase tracking-[0.18em] text-slate-400">KH. Mas Mansur</span>
          </span>
        </div>

        <span class="text-[10px] font-semibold uppercase tracking-[0.25em] text-brand">Area Admin</span>
        <h2 class="mt-2 text-2xl font-semibold text-slate-900">Login</h2>
        <p class="mt-1 text-sm text-slate-500">Gunakan email dan password akun administrator Anda.</p>

        @if ($errors->any())
          <div class="mt-5 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
            <i class="fas fa-circle-exclamation mt-0.5 text-xs"></i>
            <span>{{ $errors->first() }}</span>
          </div>
        @endif

        <form method="POST" action="{{ route('auth.login.post') }}" class="mt-6 space-y-5">
          @csrf

          <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
            <div class="relative">
              <i class="fas fa-envelope pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
              <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="nama@email.com"
                required
                autofocus
                autocomplete="username"
                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/20"
              >
            </div>
          </div>

          <div>
            <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Password</label>
            <div class="relative">
              <i class="fas fa-lock pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
              <input
                id="password"
                type="password"
                name="password"
                placeholder="••••••••"
                required
                autocomplete="current-password"
                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-brand focus:bg-white focus:ring-2 focus:ring-brand/20"
              >
            </div>
          </div>

          <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-brand py-3 text-sm font-semibold text-white transition hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand/40 focus:ring-offset-2">
            Masuk <i class="fas fa-arrow-right text-xs"></i>
          </button>
        </form>

        <a href="{{ url('/') }}" class="mt-6 inline-flex items-center gap-2 text-xs font-medium text-slate-500 transition hover:text-brand">
          <i class="fas fa-arrow-left text-[10px]"></i> Kembali ke beranda
        </a>
      </div>
    </div>
  </div>
</body>
</html>
