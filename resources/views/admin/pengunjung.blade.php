@extends('layout')

@section('content')
<div class="mb-8">
  <span class="section-kicker">Data Pengunjung</span>
  <div class="mt-2 flex flex-wrap items-end justify-between gap-4">
    <div>
      <h1 class="section-heading">Daftar Pengunjung Penginapan</h1>
      <p class="mt-2 text-sm text-base-content/60">Data tamu aktif dan yang telah checkout.</p>
    </div>
    <a href="{{ route('booking.pdf') }}" target="_blank" rel="noopener" class="btn btn-ghost btn-sm rounded-full normal-case border border-base-300">
      <i class="fas fa-file-pdf text-xs"></i> Unduh PDF
    </a>
  </div>
</div>

@php
  $rows = collect($pengunjungs)->map(function ($guest) {
    $today = now()->format('Y-m-d');
    $manualCheckout = $guest->checked_out == 1;
    $checkedOut = $guest->check_out <= $today || $manualCheckout;

    return [
      'guest' => $guest,
      'checkedOut' => $checkedOut,
      'checkoutDate' => $manualCheckout ? now()->format('Y-m-d') : $guest->check_out,
    ];
  })->sortBy(fn ($row) => $row['checkedOut'] ? 1 : 0);
@endphp

<section class="soft-card overflow-hidden">
  <div class="overflow-x-auto">
    <table class="table w-full">
      <thead class="bg-primary/5 text-primary">
        <tr>
          <th class="text-[11px] uppercase tracking-wider">Nama</th>
          <th class="text-[11px] uppercase tracking-wider">Identitas</th>
          <th class="text-[11px] uppercase tracking-wider">No Identitas</th>
          <th class="text-[11px] uppercase tracking-wider">Jenis Tamu</th>
          <th class="text-[11px] uppercase tracking-wider">Check-in</th>
          <th class="text-[11px] uppercase tracking-wider">Check-out</th>
          <th class="text-[11px] uppercase tracking-wider">Kode Kamar</th>
          <th class="text-[11px] uppercase tracking-wider">Kamar Dipilih</th>
          <th class="text-[11px] uppercase tracking-wider">Jml Kamar</th>
          <th class="text-[11px] uppercase tracking-wider">Jml Orang</th>
          <th class="text-[11px] uppercase tracking-wider">Total Harga</th>
          <th class="text-[11px] uppercase tracking-wider">Bayar</th>
          <th class="text-[11px] uppercase tracking-wider">Keterangan</th>
          <th class="text-[11px] uppercase tracking-wider">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rows as $row)
          @php
            $guest = $row['guest'];

            $roomTypes = [];
            foreach (explode(',', $guest->kode_kamar ?? '') as $roomCode) {
              $room = \App\Models\Kamar::where('kode_kamar', trim($roomCode))->first();
              if ($room && !in_array($room->jenis_kamar, $roomTypes)) {
                $roomTypes[] = $room->jenis_kamar;
              }
            }

            $phone = preg_replace('/[^0-9+]/', '', $guest->no_telp_pic ?? $guest->no_telp ?? '');
            $wa = $phone ? 'https://wa.me/' . ltrim($phone, '0') : null;
          @endphp

          <tr class="hover:bg-base-200/50 {{ $row['checkedOut'] ? 'opacity-60' : '' }}">
            <td>
              @if($guest->jenis_tamu == 'corporate')
                <span class="text-sm font-semibold">{{ $guest->nama_pic ?? 'PIC' }}</span>
                @if($guest->nama)
                  <span class="mt-0.5 block text-xs text-base-content/50">{{ $guest->nama }}</span>
                @endif
              @else
                <span class="text-sm font-medium">{{ $guest->nama }}</span>
              @endif
            </td>
            <td class="text-xs">{{ strtoupper($guest->identity_type ?? 'KTP') }}</td>
            <td class="text-xs">{{ $guest->no_identitas }}</td>
            <td>
              <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $guest->jenis_tamu == 'corporate' ? 'bg-success/10 text-success' : 'bg-info/10 text-info' }}">
                {{ ucfirst($guest->jenis_tamu) }}
              </span>
            </td>
            <td class="text-xs">{{ $guest->check_in }}</td>
            <td class="text-xs">{{ $guest->check_out }}</td>
            <td class="text-xs">{{ $guest->kode_kamar }}</td>
            <td class="text-xs font-medium">{{ implode(', ', $roomTypes) ?: '-' }}</td>
            <td class="text-sm font-medium">{{ $guest->jumlah_kamar ?? '-' }}</td>
            <td class="text-sm font-medium">{{ $guest->jumlah_peserta ?? '-' }}</td>
            <td class="text-sm font-semibold text-primary">
              {{ $guest->display_total !== null ? 'Rp ' . number_format($guest->display_total, 0, ',', '.') : '-' }}
            </td>
            <td class="text-xs">{{ $guest->payment_status_label }}</td>
            <td>
              @if($row['checkedOut'])
                <span class="inline-flex rounded-full bg-error/10 px-3 py-1 text-xs font-medium text-error">Telah checkout</span>
                <span class="mt-1 block text-[11px] text-base-content/50">{{ $row['checkoutDate'] }}</span>
              @else
                <span class="text-xs text-base-content/40">-</span>
              @endif
            </td>
            <td>
              <div class="flex items-center gap-1">
                @if($wa)
                  <a href="{{ $wa }}" target="_blank" rel="noopener" class="btn btn-ghost btn-xs rounded-full normal-case text-[#25D366] hover:bg-[#25D366]/10" aria-label="Chat WhatsApp">
                    <i class="fab fa-whatsapp text-sm"></i>
                  </a>
                @endif

                <details class="dropdown dropdown-end">
                  <summary class="btn btn-ghost btn-xs rounded-full normal-case text-secondary hover:bg-secondary/10">Aksi</summary>
                  <ul class="menu dropdown-content z-20 mt-2 w-44 rounded-box border border-base-200 bg-base-100 p-2 shadow-xl">
                    <li><a href="{{ route('pengunjung.show', $guest->id) }}">View Detail</a></li>
                    <li><a href="{{ route('pengunjung.edit', $guest->id) }}">Edit</a></li>
                    <li>
                      <form action="{{ route('pengunjung.destroy', $guest->id) }}" method="GET" class="delete-form">
                        <button type="submit" class="text-error">Hapus</button>
                      </form>
                    </li>
                  </ul>
                </details>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="14" class="py-10 text-center text-sm text-base-content/50">Belum ada data pengunjung.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.delete-form').forEach((form) => {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      Swal.fire({
        title: 'Hapus Data?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) form.submit();
      });
    });
  });
});
</script>
@endsection
