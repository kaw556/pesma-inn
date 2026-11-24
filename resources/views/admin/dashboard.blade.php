@extends('layout')

@section('content')
<div class="mb-8">
  <span class="section-kicker">Ringkasan</span>
  <h1 class="section-heading mt-2">Dashboard</h1>
  <p class="mt-2 max-w-2xl text-sm leading-relaxed text-base-content/60">
    Pantau ketersediaan kamar, jumlah tamu, dan aktivitas pemesanan Pesma Inn hari ini.
  </p>
</div>

@php
  $stats = [
    ['label' => 'Kamar tersedia', 'value' => $kamarKosong, 'hint' => 'Total kamar kosong saat ini.', 'icon' => 'fa-door-open'],
    ['label' => 'Kamar terisi', 'value' => $kamarTerisi, 'hint' => 'Kamar yang sedang ditempati tamu.', 'icon' => 'fa-bed', 'progress' => $occupancyRate],
    ['label' => 'Pengunjung hari ini', 'value' => number_format($jumlahPengunjung ?? 0), 'hint' => 'Total peserta dari seluruh booking aktif.', 'icon' => 'fa-users'],
    ['label' => 'Check-in hari ini', 'value' => number_format($checkinHariIni ?? 0), 'hint' => 'Peserta yang sudah melakukan check-in hari ini.', 'icon' => 'fa-calendar-check'],
  ];
@endphp

<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
  @foreach($stats as $stat)
    <article class="soft-card p-6">
      <div class="flex items-start justify-between gap-4">
        <div>
          <p class="text-[11px] font-semibold uppercase tracking-wider text-base-content/50">{{ $stat['label'] }}</p>
          <p class="mt-3 text-3xl font-semibold tracking-tight text-primary">{{ $stat['value'] }}</p>
        </div>
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-secondary/10 text-secondary">
          <i class="fas {{ $stat['icon'] }}"></i>
        </span>
      </div>

      <p class="mt-3 text-xs leading-relaxed text-base-content/60">{{ $stat['hint'] }}</p>

      @if(isset($stat['progress']))
        <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-base-200">
          <div class="h-full rounded-full bg-secondary" style="width: {{ $stat['progress'] }}%"></div>
        </div>
        <p class="mt-2 text-[11px] font-medium text-secondary">Occupancy {{ $stat['progress'] }}%</p>
      @endif
    </article>
  @endforeach
</div>

<div class="mt-10">
  <span class="section-kicker">Akses cepat</span>
  <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @php
      $shortcuts = [
        ['label' => 'Data Kamar', 'url' => route('kamar.index'), 'icon' => 'fa-bed'],
        ['label' => 'Data Pengunjung', 'url' => route('pengunjung.index'), 'icon' => 'fa-users'],
        ['label' => 'Pembayaran', 'url' => route('pembayaran.konfirmasi'), 'icon' => 'fa-receipt'],
        ['label' => 'Report', 'url' => route('report.monthly'), 'icon' => 'fa-chart-simple'],
      ];
    @endphp

    @foreach($shortcuts as $shortcut)
      <a href="{{ $shortcut['url'] }}" class="soft-card group flex items-center justify-between gap-3 p-5 transition hover:border-secondary hover:shadow-md">
        <span class="flex items-center gap-3">
          <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/5 text-primary">
            <i class="fas {{ $shortcut['icon'] }}"></i>
          </span>
          <span class="text-sm font-semibold">{{ $shortcut['label'] }}</span>
        </span>
        <i class="fas fa-arrow-right text-xs text-base-content/30 transition group-hover:translate-x-1 group-hover:text-secondary"></i>
      </a>
    @endforeach
  </div>
</div>

<div class="mt-10 grid gap-5 lg:grid-cols-3">
  <section class="soft-card p-6 lg:col-span-2">
    <div class="flex items-center justify-between gap-4">
      <h2 class="text-sm font-semibold text-primary">Aktivitas terbaru</h2>
      <span class="text-[11px] text-base-content/50">Pemesanan masuk</span>
    </div>

    <ul class="mt-4 divide-y divide-base-200">
      @forelse($recentBookings as $booking)
        <li class="flex items-center justify-between gap-4 py-3">
          <div class="min-w-0">
            <p class="truncate text-sm font-medium">
              @if(strtolower($booking->jenis_tamu) == 'corporate')
                {{ $booking->nama_pic ?? 'PIC' }} <span class="font-normal text-base-content/50">({{ $booking->nama }})</span>
              @else
                {{ $booking->nama }}
              @endif
            </p>
            <p class="mt-0.5 truncate text-xs text-base-content/50">
              {{ $booking->kode_kamar ?? '-' }} · {{ \Illuminate\Support\Carbon::parse($booking->created_at)->format('d M Y H:i') }}
            </p>
          </div>
          <span class="shrink-0 rounded-full bg-secondary/10 px-3 py-1 text-[11px] font-medium text-secondary">Booking</span>
        </li>
      @empty
        <li class="py-6 text-center text-sm text-base-content/50">Belum ada aktivitas pemesanan.</li>
      @endforelse
    </ul>
  </section>

  <section class="soft-card p-6">
    <h2 class="text-sm font-semibold text-primary">Hari ini & mendatang</h2>

    <div class="mt-4 flex items-center justify-between rounded-xl bg-secondary/10 px-4 py-3">
      <span class="text-xs font-medium text-base-content/70">Check-in hari ini</span>
      <span class="text-lg font-semibold text-primary">{{ $todayCheckins->count() }}</span>
    </div>

    <h3 class="mt-6 text-xs font-semibold uppercase tracking-wider text-base-content/50">Check-out mendatang</h3>
    <ul class="mt-3 divide-y divide-base-200">
      @forelse($upcomingCheckouts as $guest)
        <li class="py-2.5 text-sm">
          <p class="font-medium">
            @if(strtolower($guest->jenis_tamu) == 'corporate')
              {{ $guest->nama_pic ?? 'PIC' }}
            @else
              {{ $guest->nama }}
            @endif
          </p>
          <p class="mt-0.5 text-xs text-base-content/50">{{ $guest->check_out }}</p>
        </li>
      @empty
        <li class="py-4 text-sm text-base-content/50">Tidak ada check-out mendatang.</li>
      @endforelse
    </ul>
  </section>
</div>
@endsection
