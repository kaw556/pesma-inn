@extends('layout')

@section('content')
<div class="mb-8">
  <span class="section-kicker">Laporan</span>
  <h1 class="section-heading mt-2">Laporan Bulanan</h1>
  <p class="mt-2 text-sm text-base-content/60">Ringkasan kondisi kamar dan daftar booking untuk periode tertentu.</p>
</div>

<section class="soft-card p-5 sm:p-6">
  <div class="flex flex-wrap items-end justify-between gap-4">
    <div>
      <h2 class="text-sm font-semibold text-primary">Unduh Laporan Bulanan</h2>
      <p class="mt-1 text-sm text-base-content/60">{{ \Carbon\Carbon::parse($monthStart)->format('F Y') }}</p>
    </div>

    <form method="GET" action="{{ route('report.monthly') }}" class="flex flex-wrap items-end gap-3">
      <label class="block min-w-[200px]">
        <span class="mb-1.5 block text-xs font-medium text-base-content/70">Pilih Bulan</span>
        <select name="month" required class="select select-bordered w-full">
          <option value="">-- Pilih Bulan --</option>
          @for($i = 0; $i < 12; $i++)
            @php
              $date = now()->subMonths($i);
              $value = $date->format('Y-m');
            @endphp
            <option value="{{ $value }}" {{ $selectedMonth == $value ? 'selected' : '' }}>{{ $date->format('F Y') }}</option>
          @endfor
        </select>
      </label>
      <button type="submit" class="btn btn-primary rounded-full normal-case">Tampilkan</button>
    </form>
  </div>

  <div class="mt-5 flex flex-wrap gap-3 border-t border-base-200 pt-5">
    <a href="{{ route('report.monthly.pdf', ['month' => $selectedMonth]) }}" class="btn btn-ghost btn-sm rounded-full normal-case border border-base-300">
      <i class="fas fa-file-pdf text-xs text-error"></i> Export PDF
    </a>
    <a href="{{ route('report.monthly.csv', ['month' => $selectedMonth]) }}" class="btn btn-ghost btn-sm rounded-full normal-case border border-base-300">
      <i class="fas fa-file-csv text-xs text-success"></i> Export CSV
    </a>
  </div>
</section>

<div class="mt-6 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
  @php
    $reportStats = [
      ['label' => 'Total Kamar', 'value' => $totalKamar, 'color' => 'text-primary'],
      ['label' => 'Kamar Kosong', 'value' => $kamarKosong, 'color' => 'text-success'],
      ['label' => 'Kamar Terisi', 'value' => $kamarTerisi, 'color' => 'text-warning'],
      ['label' => 'Booking Bulan Ini', 'value' => $bookingsThisMonth->count(), 'color' => 'text-secondary'],
    ];
  @endphp

  @foreach($reportStats as $stat)
    <article class="soft-card p-6">
      <p class="text-[11px] font-semibold uppercase tracking-wider text-base-content/50">{{ $stat['label'] }}</p>
      <p class="mt-3 text-3xl font-semibold tracking-tight {{ $stat['color'] }}">{{ $stat['value'] }}</p>
    </article>
  @endforeach
</div>

<section class="soft-card mt-6 overflow-hidden">
  <div class="border-b border-base-200 px-5 py-4 sm:px-6">
    <h2 class="text-sm font-semibold text-primary">Daftar Booking Bulan Ini</h2>
  </div>

  <div class="overflow-x-auto">
    <table class="table w-full">
      <thead class="bg-primary/5 text-primary">
        <tr>
          <th class="text-[11px] uppercase tracking-wider">#</th>
          <th class="text-[11px] uppercase tracking-wider">Nama</th>
          <th class="text-[11px] uppercase tracking-wider">Jenis Tamu</th>
          <th class="text-[11px] uppercase tracking-wider">Check-in</th>
          <th class="text-[11px] uppercase tracking-wider">Check-out</th>
          <th class="text-[11px] uppercase tracking-wider">Kamar</th>
          <th class="text-[11px] uppercase tracking-wider">Status Pembayaran</th>
          <th class="text-[11px] uppercase tracking-wider">No. Telp</th>
        </tr>
      </thead>
      <tbody>
        @forelse($bookingsThisMonth as $index => $booking)
          <tr class="hover:bg-base-200/50">
            <td class="text-xs text-base-content/50">{{ $index + 1 }}</td>
            <td class="text-sm font-medium">{{ $booking->nama }}</td>
            <td>
              <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $booking->jenis_tamu == 'Corporate' ? 'bg-secondary/10 text-secondary' : 'bg-warning/15 text-warning' }}">
                {{ $booking->jenis_tamu }}
              </span>
            </td>
            <td class="text-xs">{{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}</td>
            <td class="text-xs">{{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}</td>
            <td class="text-xs">{{ $booking->kode_kamar ?? $booking->nomor_kamar ?? '-' }}</td>
            <td>
              @if(in_array($booking->payment_status, ['lunas', 'paid']))
                <span class="inline-flex rounded-full bg-success/10 px-3 py-1 text-xs font-medium text-success">{{ $booking->payment_status_label }}</span>
              @elseif($booking->payment_status == 'pending')
                <span class="inline-flex rounded-full bg-warning/15 px-3 py-1 text-xs font-medium text-warning">{{ $booking->payment_status_label }}</span>
              @elseif($booking->payment_status == 'rejected')
                <span class="inline-flex rounded-full bg-error/10 px-3 py-1 text-xs font-medium text-error">{{ $booking->payment_status_label }}</span>
              @else
                <span class="inline-flex rounded-full bg-base-200 px-3 py-1 text-xs font-medium text-base-content/60">{{ $booking->payment_status_label }}</span>
              @endif
            </td>
            <td class="text-xs">{{ $booking->no_telp_pic ?? $booking->no_telp ?? '-' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="py-12 text-center">
              <i class="fas fa-inbox text-2xl text-base-content/30"></i>
              <p class="mt-2 text-sm text-base-content/50">Tidak ada booking pada bulan ini.</p>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>
@endsection
