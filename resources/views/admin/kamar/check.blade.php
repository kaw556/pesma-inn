@extends('layout')

@section('content')
<div class="mx-auto max-w-4xl">
  <div class="mb-8">
    <span class="section-kicker">Data Kamar</span>
    <h1 class="section-heading mt-2">Check Ketersediaan</h1>
    <p class="mt-2 text-sm text-base-content/60">Kamar {{ $kamar->kode_kamar ?? $kamar->nomor_kamar }} — cek bentrok jadwal pada rentang tanggal tertentu.</p>
  </div>

  <section class="soft-card p-6 sm:p-8">
    <form method="GET" class="flex flex-wrap items-end gap-4">
      <label class="block flex-1">
        <span class="mb-1.5 block text-xs font-medium text-base-content/70">Mulai</span>
        <input type="date" name="start" value="{{ $start ?? '' }}" class="input input-bordered w-full">
      </label>
      <label class="block flex-1">
        <span class="mb-1.5 block text-xs font-medium text-base-content/70">Selesai</span>
        <input type="date" name="end" value="{{ $end ?? '' }}" class="input input-bordered w-full">
      </label>
      <button type="submit" class="btn btn-primary rounded-full normal-case">Cek</button>
      <a href="{{ route('kamar.index') }}" class="btn btn-ghost rounded-full normal-case">Kembali</a>
    </form>

    @if(!is_null($available))
      <div class="mt-6">
        @if($available)
          <div class="alert alert-success text-sm">
            <i class="fas fa-circle-check"></i>
            <span>Kamar tersedia untuk rentang tanggal yang dipilih.</span>
          </div>
        @else
          <div class="alert alert-warning text-sm">
            <i class="fas fa-triangle-exclamation"></i>
            <span>Kamar tidak tersedia. Berikut booking yang bertumpuk:</span>
          </div>

          <div class="mt-5 overflow-x-auto">
            <table class="table w-full">
              <thead class="bg-primary/5 text-primary">
                <tr>
                  <th class="text-[11px] uppercase tracking-wider">#</th>
                  <th class="text-[11px] uppercase tracking-wider">Nama</th>
                  <th class="text-[11px] uppercase tracking-wider">Check-in</th>
                  <th class="text-[11px] uppercase tracking-wider">Check-out</th>
                  <th class="text-[11px] uppercase tracking-wider">Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($overlapping as $index => $booking)
                  <tr class="hover:bg-base-200/50">
                    <td class="text-sm text-base-content/50">{{ $index + 1 }}</td>
                    <td class="text-sm font-medium">{{ $booking->nama }}</td>
                    <td class="text-xs">{{ $booking->check_in }}</td>
                    <td class="text-xs">{{ $booking->check_out }}</td>
                    <td class="text-xs">{{ $booking->payment_status }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    @endif
  </section>
</div>
@endsection
