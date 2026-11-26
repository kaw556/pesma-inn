<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" media="(prefers-color-scheme: light)" href="{{ asset('img/logo-resmi-pesma.png') }}">
    <link rel="icon" type="image/png" media="(prefers-color-scheme: dark)" href="{{ asset('img/logo-resmi-pesma-white.png') }}">
    <meta name="description" content="Pesma Inn KH. Mas Mansur — penginapan nyaman, terjangkau, dan strategis di Surakarta.">
    <title>Guest House KH. Mas Mansur</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-base-100 text-base-content antialiased"
    data-booking-individu="{{ route('booking.individu') }}"
    data-booking-corporate="{{ route('booking.corporate') }}"
    data-check-kamar="{{ route('cek.kamar') }}"
>
@php
    $heroImage = ($data->slider1_image && file_exists(public_path($data->slider1_image)))
        ? asset($data->slider1_image)
        : asset('img/bg-masjid.JPG');

    $photos = [
        ['src' => asset('img/bg-masjid.JPG'),         'title' => 'Suasana Pesma KH. Mas Mansur'],
        ['src' => asset('img/bg-masjid-2.JPG'),       'title' => 'Lingkungan Pesma'],
        ['src' => asset('images/Standar Room.jpg'),   'title' => 'Standar Room'],
        ['src' => asset('images/Deluxe.jpg'),         'title' => 'Deluxe Room'],
        ['src' => asset('images/Guestroom AC.jpeg'),  'title' => 'Guestroom AC'],
        ['src' => asset('images/Student Non AC.jpg'), 'title' => 'Student Non AC'],
        ['src' => asset('images/Student AC.jpeg'),    'title' => 'Student AC'],
        ['src' => asset('images/room.jpeg'),          'title' => 'Kamar Tamu'],
    ];
@endphp

{{-- ============================================================
     NAVBAR — transparan di atas hero
============================================================ --}}
<header id="siteNav" class="navbar fixed inset-x-0 top-0 z-50 bg-transparent text-white transition-all duration-300">
    <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-5 py-3 lg:px-8">
        <a href="#hero" class="nav-logo btn btn-ghost gap-2 border border-0 px-1 normal-case hover:bg-white/10">
            <img src="{{ asset('img/logo-resmi-pesma.png') }}" alt="Logo Pesma" class="h-9 w-9 object-contain">
            <span class="hidden text-left leading-tight sm:block">
                <span class="block text-base font-semibold">Pesma Inn</span>
                <span class="block text-[10px] font-normal uppercase tracking-[0.18em] opacity-80">KH. Mas Mansur</span>
            </span>
        </a>

        <nav class="hidden items-center gap-8 lg:flex" aria-label="Navigasi utama">
            <a href="#hero" class="nav-link">Beranda</a>
            <a href="#tentang" class="nav-link">Tentang</a>
            <a href="#pemesanan-kamar" class="nav-link">Kamar</a>
            <a href="#galeri" class="nav-link">Galeri</a>
            <a href="{{ route('auth.login') }}" class="btn btn-secondary btn-sm rounded-full px-5">Login</a>
        </nav>

        <details class="dropdown dropdown-end lg:hidden">
            <summary class="btn btn-ghost btn-circle hover:bg-white/10" aria-label="Buka menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </summary>
            <ul class="menu dropdown-content mt-3 w-56 rounded-box border border-base-200 bg-base-100 p-2 text-base-content shadow-xl">
                <li><a href="#hero">Beranda</a></li>
                <li><a href="#tentang">Tentang</a></li>
                <li><a href="#pemesanan-kamar">Kamar</a></li>
                <li><a href="#galeri">Galeri</a></li>
                <li><a href="{{ route('auth.login') }}" class="text-secondary">Login</a></li>
            </ul>
        </details>
    </div>
</header>

<main>
    {{-- ============================================================
         SECTION 1 — HERO (gambar statis)
    ============================================================ --}}
    <section id="hero" class="relative flex min-h-screen items-center overflow-hidden">
        <img src="{{ $heroImage }}" alt="Pesma Inn KH. Mas Mansur" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-black/45 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-primary/90 via-primary/50 to-primary/10"></div>
        <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-base-100 to-transparent"></div>

        <div class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-24 pt-32 lg:px-8">
            <div class="max-w-2xl text-white">

                <h1 class="mt-6 whitespace-pre-line text-5xl font-semibold leading-[1.05] tracking-tight md:text-6xl lg:text-7xl">
                    {{ $data->slider1_text }}
                </h1>

                <p class="mt-6 max-w-xl text-base leading-relaxed text-white/85 md:text-lg">
                    Fasilitas setara hotel, harga bersahabat, dan lokasi strategis dekat Kampus UMS.
                    Tempat pulang yang tenang untuk setiap perjalanan Anda.
                </p>

                <div class="mt-9 flex flex-wrap gap-3">
                    <a href="#pemesanan-kamar" class="btn btn-secondary rounded-full px-7">
                        Pesan Kamar <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                    <a href="#galeri" class="btn btn-ghost rounded-full border border-white/40 px-7 text-white hover:bg-white/10">
                        Lihat Galeri
                    </a>
                </div>

                <dl class="mt-14 flex flex-wrap gap-x-10 gap-y-5 border-t border-white/20 pt-6">
                    <div>
                        <dt class="text-[10px] font-semibold uppercase tracking-[0.2em] text-white/60">Lokasi</dt>
                        <dd class="mt-1 text-sm font-medium">Area Kampus 4 UMS</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-semibold uppercase tracking-[0.2em] text-white/60">Pilihan Kamar</dt>
                        <dd class="mt-1 text-sm font-medium">{{ $roomTypes->count() }} tipe kamar</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-semibold uppercase tracking-[0.2em] text-white/60">Tamu</dt>
                        <dd class="mt-1 text-sm font-medium">Individu & Korporasi</dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    {{-- ============================================================
         SECTION 2 — ABOUT
    ============================================================ --}}
    <section id="tentang" class="bg-base-100 py-24">
        <div class="mx-auto grid max-w-7xl items-center gap-16 px-5 lg:grid-cols-2 lg:px-8">
            <div>
                <span class="section-kicker">Tentang Kami</span>
                <h2 class="section-heading mt-3">Tinggal lebih tenang, dekat dengan semua.</h2>
                <p class="mt-5 leading-relaxed text-base-content/70">
                    Pesma Inn KH. Mas Mansur menghadirkan pengalaman menginap yang sederhana namun nyaman.
                    Berada di lingkungan Pesantren Mahasiswa KH. Mas Mansur, kami menyambut tamu individu
                    maupun rombongan korporasi dengan pelayanan yang hangat dan perhatian pada detail.
                </p>
                <p class="mt-4 leading-relaxed text-base-content/70">
                    Setiap kamar dipersiapkan agar Anda bisa beristirahat dengan baik setelah seharian
                    beraktivitas — dengan lokasi yang mudah dijangkau dari kampus dan pusat kota.
                </p>

                <div class="mt-9 grid gap-4 sm:grid-cols-3">
                    <div class="soft-card p-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-secondary/10 text-secondary">
                            <i class="fas fa-location-dot"></i>
                        </span>
                        <h3 class="mt-4 text-sm font-semibold">Lokasi Strategis</h3>
                        <p class="mt-1 text-xs leading-relaxed text-base-content/60">Dekat kampus dan akses transportasi.</p>
                    </div>
                    <div class="soft-card p-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-secondary/10 text-secondary">
                            <i class="fas fa-bed"></i>
                        </span>
                        <h3 class="mt-4 text-sm font-semibold">Kamar Nyaman</h3>
                        <p class="mt-1 text-xs leading-relaxed text-base-content/60">Bersih dan fasilitas setara hotel.</p>
                    </div>
                    <div class="soft-card p-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-secondary/10 text-secondary">
                            <i class="fas fa-wallet"></i>
                        </span>
                        <h3 class="mt-4 text-sm font-semibold">Harga Bersahabat</h3>
                        <p class="mt-1 text-xs leading-relaxed text-base-content/60">Pilihan sesuai kebutuhan Anda.</p>
                    </div>
                </div>
            </div>

            <div class="relative mx-auto w-full max-w-lg">
                <div class="aspect-[4/5] overflow-hidden rounded-3xl shadow-2xl">
                    <img src="{{ asset('img/bg-masjid.JPG') }}" alt="Bangunan Pesma KH. Mas Mansur" loading="lazy" class="h-full w-full object-cover">
                </div>
                <div class="absolute -bottom-8 -left-6 hidden h-40 w-40 overflow-hidden rounded-2xl border-8 border-base-100 shadow-xl sm:block">
                    <img src="{{ asset('images/Guestroom AC.jpeg') }}" alt="Guestroom AC" loading="lazy" class="h-full w-full object-cover">
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         SECTION 3 — PEMESANAN KAMAR
    ============================================================ --}}
    <section id="pemesanan-kamar" class="bg-base-200/50 py-24">
        <div class="mx-auto max-w-7xl px-5 lg:px-8">
            <header class="mx-auto max-w-2xl text-center">
                <span class="section-kicker">Pemesanan Kamar</span>
                <h2 class="section-heading mt-3">Pilih kamar yang pas untuk Anda</h2>
                <p class="mt-4 text-base-content/70">
                    Cek ketersediaan untuk tanggal menginap Anda, lalu lanjutkan dengan form pemesanan.
                </p>
            </header>

            <form id="cekForm" class="soft-card mt-10 grid gap-4 p-4 md:grid-cols-[1fr_1fr_0.7fr_auto] md:items-end md:p-5">
                <label class="form-control">
                    <span class="label-text text-xs font-medium text-base-content/70">Check In</span>
                    <input type="date" id="checkin" name="checkin" required value="{{ request('checkin') }}" class="input input-bordered w-full">
                </label>
                <label class="form-control">
                    <span class="label-text text-xs font-medium text-base-content/70">Check Out</span>
                    <input type="date" id="checkout" name="checkout" required value="{{ request('checkout') }}" class="input input-bordered w-full">
                </label>
                <label class="form-control">
                    <span class="label-text text-xs font-medium text-base-content/70">Jumlah Kamar</span>
                    <input type="number" id="jumlahkamar" name="jumlahkamar" min="1" placeholder="1" required value="{{ request('jumlahkamar', 1) }}" class="input input-bordered w-full">
                </label>
                <button type="submit" class="btn btn-primary h-12">Cek Kamar</button>
            </form>

            <div class="card-wrapper swiper mt-12">
                <ul class="swiper-wrapper !list-none !p-0">
                    @forelse($roomTypes as $room)
                        @php
                            $fasilitasList = explode(',', $room->fasilitas);
                            $fasilitasPreview = array_slice($fasilitasList, 0, 2);

                            $foto = \App\Models\Kamar::fotoJenisPath($room->jenis_kamar) ?? 'images/room.jpeg';
                        @endphp
                        <li class="swiper-slide h-auto">
                            <article class="card h-full overflow-hidden rounded-2xl border border-base-200 bg-base-100 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                                <figure class="relative h-52 overflow-hidden">
                                    <img src="{{ asset($foto) }}" alt="{{ $room->jenis_kamar }}" loading="lazy" class="h-full w-full object-cover">
                                </figure>
                                <div class="card-body gap-3 p-6">
                                    <div class="flex items-start justify-between gap-3">
                                        <h3 class="card-title text-primary">{{ $room->jenis_kamar }}</h3>
                                        <small class="availability-text text-right text-xs" data-room="{{ $room->jenis_kamar }}">
                                            @if(isset($room->tersisa))
                                                <span class="{{ $room->tersisa > 0 ? 'text-success' : 'text-error' }}">
                                                    {{ $room->tersisa > 0 ? 'Tersisa ' . $room->tersisa : 'Habis' }}
                                                </span>
                                            @endif
                                        </small>
                                    </div>

                                    <ul class="space-y-1 text-sm text-base-content/70">
                                        @foreach($fasilitasPreview as $f)
                                            <li class="flex gap-2">
                                                <i class="fas fa-check mt-1 text-xs text-secondary"></i>
                                                <span>{{ trim($f) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="mt-2 flex items-end justify-between border-t border-base-200 pt-4">
                                        <div>
                                            <p class="text-[10px] font-semibold uppercase tracking-wider text-base-content/50">Mulai dari</p>
                                            <p class="text-lg font-semibold text-primary">Rp {{ number_format($room->harga, 0, ',', '.') }}</p>
                                        </div>
                                        <button
                                            type="button"
                                            class="see-more btn btn-ghost btn-sm"
                                            data-room="{{ $room->jenis_kamar }}"
                                            data-fasilitas="{{ $room->fasilitas }}"
                                            data-harga="{{ $room->harga }}"
                                            data-kamar="{{ $room->kamar_id }}"
                                        >Detail</button>
                                    </div>

                                    <div class="dropdown dropdown-top dropdown-end mt-1">
                                        <div tabindex="-1" role="button" class="book-now btn btn-primary btn-sm w-full rounded-full" data-locked="true">
                                            Cek dulu ▾
                                        </div>
                                        <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-56 rounded-box border border-base-200 bg-base-100 p-2 shadow-xl">
                                            <li>
                                                <button type="button" class="booking-option" data-type="corporate" data-kamar="{{ $room->kamar_id }}">Form Korporasi</button>
                                            </li>
                                            <li>
                                                <button type="button" class="booking-option" data-type="individu" data-kamar="{{ $room->kamar_id }}">Form Individu</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </article>
                        </li>
                    @empty
                        <li class="py-10 text-center text-base-content/60">
                            Belum ada tipe kamar yang tersedia untuk tanggal {{ $checkIn }} - {{ $checkOut }}.
                        </li>
                    @endforelse
                </ul>

                <div class="mt-8 flex items-center justify-center gap-4">
                    <button type="button" class="room-prev btn btn-circle btn-sm shrink-0 border border-base-300 bg-base-100 text-primary shadow-sm transition hover:border-secondary hover:text-secondary" aria-label="Kamar sebelumnya">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <div class="swiper-pagination !static !w-auto"></div>
                    <button type="button" class="room-next btn btn-circle btn-sm shrink-0 border border-base-300 bg-base-100 text-primary shadow-sm transition hover:border-secondary hover:text-secondary" aria-label="Kamar berikutnya">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Dialog detail kamar --}}
        <dialog id="roomPopup" class="modal">
            <div class="modal-box max-w-lg">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" aria-label="Tutup">✕</button>
                </form>
                <span class="section-kicker">Detail Kamar</span>
                <h3 id="popupTitle" class="mt-2 text-2xl font-semibold text-primary"></h3>
                <ul id="popupFeatures" class="mt-4 space-y-2 text-sm text-base-content/70"></ul>
                <p class="mt-5 text-sm">
                    <span class="font-medium">Harga:</span>
                    <span id="popupPrice" class="font-semibold text-primary"></span>
                </p>
                <div class="modal-action">
                    <button type="button" id="popupBook" class="btn btn-primary rounded-full" data-kamar="">Pesan Kamar Ini</button>
                </div>
            </div>
        </dialog>
    </section>

    {{-- ============================================================
         SECTION 4 — GALERI FOTO
    ============================================================ --}}
    <section id="galeri" class="bg-base-100 py-24">
        <div class="mx-auto max-w-7xl px-5 lg:px-8">
            <header class="mx-auto max-w-2xl text-center">
                <span class="section-kicker">Galeri Foto</span>
                <h2 class="section-heading mt-3">Suasana & fasilitas Pesma Inn</h2>
                <p class="mt-4 text-base-content/70">Lihat lebih dekat lingkungan dan pilihan kamar yang siap menyambut Anda.</p>
            </header>

            <div class="mt-12 grid grid-cols-2 gap-4 md:grid-cols-4">
                @foreach($photos as $index => $photo)
                    <button type="button" class="gallery-item group relative aspect-square overflow-hidden rounded-2xl" data-index="{{ $index }}" aria-label="Lihat foto {{ $photo['title'] }}">
                        <img src="{{ $photo['src'] }}" alt="{{ $photo['title'] }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
                        <span class="absolute inset-x-0 bottom-0 translate-y-full bg-gradient-to-t from-black/80 to-transparent p-4 pt-10 text-left text-sm text-white transition duration-300 group-hover:translate-y-0">
                            {{ $photo['title'] }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Lightbox galeri --}}
    <dialog id="galleryLightbox" class="modal !bg-black/80 backdrop-blur-sm">
        <div class="modal-box max-w-5xl bg-transparent p-0 shadow-none">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 z-10 text-white" aria-label="Tutup">✕</button>
            </form>
            <figure>
                <img id="lightboxImage" src="" alt="" class="max-h-[70vh] w-full rounded-2xl object-contain">
            </figure>
            <figcaption id="lightboxCaption" class="mt-4 text-center text-sm text-white"></figcaption>
            <div class="mt-5 flex justify-center gap-3">
                <button type="button" id="lightboxPrev" class="btn btn-circle btn-ghost text-white" aria-label="Foto sebelumnya">❮</button>
                <button type="button" id="lightboxNext" class="btn btn-circle btn-ghost text-white" aria-label="Foto berikutnya">❯</button>
            </div>
        </div>
    </dialog>
</main>

{{-- ============================================================
     SECTION 5 — FOOTER
============================================================ --}}
<footer id="kontak" class="bg-primary text-primary-content">
    <div class="mx-auto grid max-w-7xl gap-10 px-5 py-16 sm:grid-cols-2 lg:grid-cols-12 lg:px-8">
        <div class="sm:col-span-2 lg:col-span-4">
            <a href="#hero" class="flex items-center gap-3">
                <img src="{{ asset('img/logo-resmi-pesma.png') }}" alt="Logo Pesma" class="footer-logo h-12 w-12 object-contain">
                <span class="leading-tight">
                    <span class="block text-lg font-semibold">Pesma Inn</span>
                    <span class="block text-[10px] uppercase tracking-[0.18em] opacity-70">KH. Mas Mansur</span>
                </span>
            </a>
            <p class="mt-4 max-w-xs text-sm leading-relaxed text-primary-content/70">
                Penginapan nyaman dan terjangkau di lingkungan Pesantren Mahasiswa KH. Mas Mansur.
                <br>Universitas Muhammadiyah Surakarta.
            </p>
        </div>

        <nav class="lg:col-span-3" aria-label="Jelajahi Pesma">
            <h2 class="text-[10px] font-semibold uppercase tracking-[0.2em] text-primary-content/50">Jelajahi</h2>
            <ul class="mt-4 space-y-2 text-sm">
                <li>
                    <a href="https://mypesma.ums.ac.id" target="_blank" rel="noopener" class="inline-flex items-start gap-2 opacity-80 transition hover:opacity-100">
                        Sistem Pengelolaan Mahasantri <i class="fas fa-arrow-up-right-from-square mt-1 text-[10px] opacity-60"></i>
                    </a>
                </li>
                <li>
                    <a href="https://mypesma.ums.ac.id/saran-keluhan" target="_blank" rel="noopener" class="inline-flex items-start gap-2 opacity-80 transition hover:opacity-100">
                        Saran & Keluhan <i class="fas fa-arrow-up-right-from-square mt-1 text-[10px] opacity-60"></i>
                    </a>
                </li>
                <li>
                    <a href="https://pesma.ums.ac.id" target="_blank" rel="noopener" class="inline-flex items-start gap-2 opacity-80 transition hover:opacity-100">
                        Profil Pesma <i class="fas fa-arrow-up-right-from-square mt-1 text-[10px] opacity-60"></i>
                    </a>
                </li>
                <li>
                    <a href="https://mypesma.ums.ac.id/layanan/peminjaman" target="_blank" rel="noopener" class="inline-flex items-start gap-2 opacity-80 transition hover:opacity-100">
                        Peminjaman Fasilitas <i class="fas fa-arrow-up-right-from-square mt-1 text-[10px] opacity-60"></i>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="lg:col-span-5">
            <h2 class="text-[10px] font-semibold uppercase tracking-[0.2em] text-primary-content/50">Kontak</h2>
            <ul class="mt-4 space-y-2 text-sm">
                <li class="flex gap-2">
                    <i class="fa-brands fa-instagram mt-1 text-xs text-secondary"></i>
                    <a href="https://www.instagram.com/{{ $data->instagram }}" target="_blank" rel="noopener" class="opacity-80 transition hover:opacity-100">{{ $data->instagram }}</a>
                </li>
                <li class="flex gap-2">
                    <i class="fa-brands fa-whatsapp mt-1 text-xs text-secondary"></i>
                    <a href="https://wa.me/{{ $data->whatsapp }}" target="_blank" rel="noopener" class="opacity-80 transition hover:opacity-100">{{ $data->whatsapp }}</a>
                </li>
                <li class="flex gap-2">
                    <i class="fas fa-envelope mt-1 text-xs text-secondary"></i>
                    <a href="mailto:{{ $data->email }}" class="opacity-80 transition hover:opacity-100">{{ $data->email }}</a>
                </li>
                <li class="flex gap-2">
                    <i class="fas fa-map-location-dot mt-1 text-xs text-secondary"></i>
                    <a href="{{ $data->maps_link }}" target="_blank" rel="noopener" class="opacity-80 transition hover:opacity-100">{{ $data->location }}</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-7xl items-center justify-center gap-4 px-5 py-6 text-xs text-primary-content/50 lg:px-8">
            <p>© {{ date('Y') }} Pesma Inn KH. Mas Mansur. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>
</html>
