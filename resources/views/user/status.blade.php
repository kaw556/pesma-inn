@extends('layout')

@section('content')
<div class="mb-8">
  <span class="section-kicker">System Status</span>
  <h1 class="section-heading mt-2">User Check</h1>
  <p class="mt-2 text-sm text-base-content/60">Ringkasan singkat untuk mengecek fungsionalitas sistem dari sisi user.</p>
</div>

<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
  @php
    $statusStats = [
      ['label' => 'Total Kamar', 'value' => $totalKamar],
      ['label' => 'Kamar Kosong', 'value' => $kamarKosong],
      ['label' => 'Jumlah Pengunjung', 'value' => $jumlahPengunjung],
      ['label' => 'Pembayaran Pending', 'value' => $pendingPayments],
    ];
  @endphp

  @foreach($statusStats as $stat)
    <article class="soft-card p-6">
      <p class="text-[11px] font-semibold uppercase tracking-wider text-base-content/50">{{ $stat['label'] }}</p>
      <p class="mt-3 text-3xl font-semibold tracking-tight text-primary">{{ $stat['value'] }}</p>
    </article>
  @endforeach
</div>

<section class="soft-card mt-6 p-6">
  <h2 class="text-sm font-semibold text-primary">Link cepat</h2>
  <div class="mt-4 flex flex-wrap gap-3">
    <a href="{{ route('booking.individu') }}" class="btn btn-ghost btn-sm rounded-full normal-case border border-base-300">Booking Individu</a>
    <a href="{{ route('booking.corporate') }}" class="btn btn-ghost btn-sm rounded-full normal-case border border-base-300">Booking Corporate</a>
    <a href="{{ route('pengunjung.index') }}" class="btn btn-ghost btn-sm rounded-full normal-case border border-base-300">Data Pengunjung (admin)</a>
  </div>
</section>
@endsection
