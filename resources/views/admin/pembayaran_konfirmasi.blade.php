@extends('layout')

@section('content')
<div class="mb-8">
  <span class="section-kicker">Pembayaran</span>
  <div class="mt-2 flex flex-wrap items-end justify-between gap-4">
    <div>
      <h1 class="section-heading">Konfirmasi Pembayaran</h1>
      <p class="mt-2 text-sm text-base-content/60">Periksa bukti bayar dan setujui atau tolak pemesanan tamu.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost btn-sm rounded-full normal-case border border-base-300">Kembali</a>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success mb-6 text-sm">{{ session('success') }}</div>
@endif

@if($errors->any())
  <div class="alert alert-error mb-6 text-sm">
    <ul class="list-disc pl-5">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<section class="soft-card overflow-hidden">
  <div class="border-b border-base-200 px-5 py-4 sm:px-6">
    <h2 class="text-sm font-semibold text-primary">Menunggu Konfirmasi</h2>
  </div>

  <div class="overflow-x-auto">
    <table class="table w-full">
      <thead class="bg-primary/5 text-primary">
        <tr>
          <th class="text-[11px] uppercase tracking-wider">No</th>
          <th class="text-[11px] uppercase tracking-wider">Nama Tamu</th>
          <th class="text-[11px] uppercase tracking-wider">Jenis</th>
          <th class="text-[11px] uppercase tracking-wider">Metode Bayar</th>
          <th class="text-[11px] uppercase tracking-wider">Check-in / out</th>
          <th class="text-[11px] uppercase tracking-wider">Kode Kamar</th>
          <th class="text-[11px] uppercase tracking-wider">Kamar Dipilih</th>
          <th class="text-[11px] uppercase tracking-wider">Kontak</th>
          <th class="text-[11px] uppercase tracking-wider">Status</th>
          <th class="text-[11px] uppercase tracking-wider">Bukti Bayar</th>
          <th class="text-[11px] uppercase tracking-wider">Total Harga</th>
          <th class="text-[11px] uppercase tracking-wider">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pengunjungs as $guest)
          @php
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
          <tr class="hover:bg-base-200/50">
            <td class="text-xs text-base-content/50">{{ $loop->iteration }}</td>
            <td>
              @if(strtolower($guest->jenis_tamu) == 'corporate')
                <span class="text-sm font-semibold">{{ $guest->nama_pic ?? 'PIC' }}</span>
                @if($guest->nama)
                  <span class="mt-0.5 block text-xs text-base-content/50">{{ $guest->nama }}</span>
                @endif
              @else
                <span class="text-sm font-medium">{{ $guest->nama }}</span>
              @endif
            </td>
            <td>
              <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ strtolower($guest->jenis_tamu) == 'corporate' ? 'bg-success/10 text-success' : 'bg-info/10 text-info' }}">
                {{ ucfirst($guest->jenis_tamu) }}
              </span>
            </td>
            <td class="text-xs font-medium text-primary">{{ $guest->metode_pembayaran ?? '-' }}</td>
            <td class="text-xs">{{ $guest->check_in }}<br>s/d {{ $guest->check_out }}</td>
            <td class="text-xs">{{ $guest->kode_kamar ?? '-' }}</td>
            <td class="text-xs font-medium">{{ implode(', ', $roomTypes) ?: '-' }}</td>
            <td>
              @if($wa)
                <a href="{{ $wa }}" target="_blank" rel="noopener" class="btn btn-ghost btn-sm font-medium rounded-full normal-case" aria-label="Chat WhatsApp">
                  <i class="fa-brands fa-square-whatsapp text-[#25D366] hover:bg-[#25D366]/10"></i> {{ $guest->no_telp_pic ?? $guest->no_telp ?? '-' }}
                </a>
              @else
                <span class="text-xs">{{ $guest->no_telp ?? '-' }}</span>
              @endif
            </td>
            <td>
              <span class="inline-flex rounded-full bg-warning/15 px-3 py-1 text-xs font-medium text-warning">
                {{ $guest->payment_status_label }}
              </span>
            </td>
            <td>
              @if($guest->bukti_pembayaran)
                @php
                  $relativePath = \Illuminate\Support\Str::startsWith($guest->bukti_pembayaran, 'public/')
                    ? substr($guest->bukti_pembayaran, 7)
                    : $guest->bukti_pembayaran;
                  $proofExists = Storage::disk('public')->exists($relativePath);
                @endphp
                @if($proofExists)
                  <a href="{{ Storage::url($relativePath) }}" target="_blank" rel="noopener" class="text-xs font-medium text-secondary hover:underline">Lihat Bukti</a>
                @else
                  <span class="text-xs text-error">File hilang</span>
                @endif
              @elseif($guest->metode_pembayaran == 'Via Cash')
                <span class="text-xs font-medium text-success">Bayar di Tempat</span>
              @else
                <span class="text-xs text-base-content/40">Belum upload</span>
              @endif
            </td>
            <td class="text-sm font-semibold text-primary">
              {{ $guest->total_harga ? 'Rp ' . number_format($guest->total_harga, 0, ',', '.') : '-' }}
            </td>
            <td>
              <div class="inline-flex items-center gap-2">
                {{-- a. Validasi --}}
                @if($guest->metode_pembayaran == 'Via Cash')
                  <form action="{{ route('pengunjung.approve.cash', $guest->id) }}" method="POST" class="cash-form" data-total="{{ $guest->total_harga }}">
                    @csrf
                    <input type="hidden" name="jumlah_uang" value="{{ $guest->total_harga }}">
                    <button type="submit" class="btn btn-primary btn-sm">
                      <i class="fa-solid fa-check-to-slot"></i>
                    </button>
                  </form>
                @else
                <form id="approveForm{{ $guest->id }}" action="{{ route('pengunjung.approve', $guest->id) }}" method="POST" class="hidden">@csrf</form>
                <form id="rejectForm{{ $guest->id }}" action="{{ route('pengunjung.reject', $guest->id) }}" method="POST" class="hidden">@csrf</form>
                  <button type="button" class="btn btn-primary btn-sm validate-transfer" data-approve="approveForm{{ $guest->id }}" data-reject="rejectForm{{ $guest->id }}" data-disabled="{{ $guest->bukti_pembayaran ? '0' : '1' }}">
                    <i class="fa-solid fa-check-to-slot"></i>
                  </button>

                @endif

                {{-- b. Cetak invoice --}}
                <a href="{{ route('booking.voucher', $guest->id) }}" target="_blank" rel="noopener" class="btn btn-ghost btn-sm border border-base-300" aria-label="Cetak Invoice">
                  <i class="fas fa-print"></i>
                </a>

                {{-- c. Hapus --}}
                <form action="{{ route('pengunjung.destroy', $guest->id) }}" method="GET" class="delete-booking">
                  <button type="submit" class="btn btn-error btn-sm">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="12" class="py-10 text-center text-sm text-base-content/50">Tidak ada pembayaran yang perlu dikonfirmasi.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>

<section class="soft-card mt-6 p-5 sm:p-6">
  <span class="section-kicker">Bukti Pembayaran</span>
  <h2 class="mt-2 text-base font-semibold text-primary">Upload manual</h2>
  <p class="mt-1 text-sm text-base-content/60">Gunakan form ini jika tamu mengirim bukti bayar via email atau WhatsApp.</p>

  <form action="#" method="POST" enctype="multipart/form-data" id="uploadPaymentForm" class="mt-5 flex flex-wrap items-end gap-4">
    @csrf
    <label class="block min-w-[220px] flex-1">
      <span class="mb-1.5 block text-xs font-medium text-base-content/70">Pilih Booking</span>
      <select name="booking_id" required class="select select-bordered w-full">
        <option value="">-- Pilih --</option>
        @foreach($pengunjungs as $guest)
          <option value="{{ $guest->id }}">{{ $guest->nama }} — {{ $guest->check_in }}</option>
        @endforeach
      </select>
    </label>

    <label class="block min-w-[220px] flex-1">
      <span class="mb-1.5 block text-xs font-medium text-base-content/70">File Bukti</span>
      <input type="file" name="bukti_pembayaran" required accept=".jpg,.jpeg,.png,.pdf" class="file-input file-input-bordered w-full">
    </label>

    <button type="submit" class="btn btn-primary rounded-full normal-case">Upload</button>
  </form>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Cash: pop-up input nominal, lalu OK / Batal
  document.querySelectorAll('.cash-form').forEach((form) => {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      Swal.fire({
        title: 'Konfirmasi Uang Cash',
        input: 'number',
        inputLabel: 'Jumlah uang diterima (Rp)',
        inputValue: form.dataset.total,
        showCancelButton: true,
        confirmButtonColor: '#0d0b61',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'OK',
        cancelButtonText: 'Batal',
        inputValidator: (value) => {
          if (value === '' || Number(value) < 0) return 'Masukkan nominal yang valid.';
        },
      }).then((result) => {
        if (result.isConfirmed) {
          form.querySelector('input[name="jumlah_uang"]').value = result.value;
          form.submit();
        }
      });
    });
  });

  // Transfer / m-banking: pop-up konfirmasi atau tolak
  document.querySelectorAll('.validate-transfer').forEach((button) => {
    button.addEventListener('click', () => {
      if (button.dataset.disabled === '1') {
        Swal.fire({ icon: 'warning', title: 'Belum ada bukti', text: 'Tamu belum mengupload bukti pembayaran.', confirmButtonColor: '#0d0b61' });
        return;
      }

      Swal.fire({
        title: 'Validasi Pembayaran',
        text: 'Setujui atau tolak booking ini?',
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        confirmButtonColor: '#16a34a',
        denyButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Konfirmasi',
        denyButtonText: 'Tolak',
        cancelButtonText: 'Batal',
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) document.getElementById(button.dataset.approve)?.submit();
        else if (result.isDenied) document.getElementById(button.dataset.reject)?.submit();
      });
    });
  });

  document.querySelectorAll('.delete-booking').forEach((form) => {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      Swal.fire({
        title: 'Hapus Data?',
        text: 'Data pengunjung akan dihapus.',
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

  const uploadForm = document.getElementById('uploadPaymentForm');
  uploadForm?.addEventListener('submit', (event) => {
    const select = uploadForm.querySelector('select[name="booking_id"]');
    if (!select?.value) {
      event.preventDefault();
      alert('Silakan pilih booking terlebih dahulu.');
      return;
    }
    uploadForm.action = '/admin/pengunjung/' + select.value + '/upload-payment';
  });
});
</script>
@endsection
