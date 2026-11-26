@extends('layouts.app')

@section('title', 'Pembayaran · Pesma Inn')

@section('content')
<style>
  .accordion-content { display: none; }
  .accordion-content.show { display: block; }
</style>

<div class="mb-8">
  <span class="section-kicker">Pembayaran</span>
  <h1 class="section-heading mt-2">Detail Pembayaran</h1>
  <p class="mt-2 text-sm text-base-content/60">Pilih metode pembayaran dan selesaikan pemesanan Anda.</p>
</div>

<div class="soft-card mb-6 flex flex-wrap items-center gap-3 p-4 text-xs font-semibold sm:gap-5">
  <span class="flex items-center gap-2 text-primary">
    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary text-[10px] text-white"><i class="fas fa-check"></i></span>
    Isi Data
  </span>
  <span class="h-px min-w-[30px] flex-1 bg-base-300"></span>
  <span class="flex items-center gap-2 text-primary">
    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary text-[10px] text-white"><i class="fas fa-check"></i></span>
    Booking
  </span>
  <span class="h-px min-w-[30px] flex-1 bg-base-300"></span>
  <span class="flex items-center gap-2 text-primary">
    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-primary text-[10px] text-white"><i class="fas fa-check"></i></span>
    Payment
  </span>
</div>

<div class="alert alert-success mb-6 text-sm">
  <i class="fas fa-circle-check text-lg"></i>
  <div>
    <p class="font-semibold">Pesanan Anda tersimpan</p>
    <p class="mt-0.5 text-base-content/70">Selesaikan sesuai metode yang dipilih. Bukti transfer diverifikasi maksimal 24 jam; untuk cash, bayar di kasir saat check-in.</p>
  </div>
</div>

<div class="grid gap-6 lg:grid-cols-[1.4fr_1fr]">
  <section class="soft-card p-6 sm:p-8">
    <h2 class="text-lg font-semibold text-primary">Detail Booking</h2>

    <div class="mt-5 grid gap-5 sm:grid-cols-2">
      <div>
        <p class="text-[11px] font-semibold uppercase tracking-wider text-base-content/50">Nama</p>
        <p class="mt-1 text-sm font-medium">{{ $pengunjung->nama ?? $pengunjung->nama_pic ?? '-' }}</p>
      </div>
      <div>
        <p class="text-[11px] font-semibold uppercase tracking-wider text-base-content/50">No Telepon</p>
        <p class="mt-1 text-sm font-medium">{{ $pengunjung->no_telp ?? $pengunjung->no_telp_pic ?? '-' }}</p>
      </div>
      <div>
        <p class="text-[11px] font-semibold uppercase tracking-wider text-base-content/50">Check-in</p>
        <p class="mt-1 text-sm font-medium">{{ \Carbon\Carbon::parse($pengunjung->check_in)->translatedFormat('l, d F Y') }}</p>
        <p class="text-xs text-base-content/50">12.00 WIB</p>
      </div>
      <div>
        <p class="text-[11px] font-semibold uppercase tracking-wider text-base-content/50">Check-out</p>
        <p class="mt-1 text-sm font-medium">{{ \Carbon\Carbon::parse($pengunjung->check_out)->translatedFormat('l, d F Y') }}</p>
        <p class="text-xs text-base-content/50">12.00 WIB</p>
      </div>
      <div>
        <p class="text-[11px] font-semibold uppercase tracking-wider text-base-content/50">Reservasi</p>
        <p class="mt-1 text-sm font-medium">{{ $durasi ?? '-' }} hari</p>
      </div>
    </div>

    <hr class="my-6 border-base-200">

    <h2 class="text-lg font-semibold text-primary">Metode Pembayaran</h2>

    <div class="mt-4 space-y-3">
      @foreach([
        ['value' => 'Via ATM', 'title' => 'Via ATM', 'steps' => [
          'Masukkan kartu ATM dan PIN Anda.',
          'Pilih menu Transfer → Bank Lain / Transfer Antar Bank.',
          'Masukkan kode bank Bukopin (441) + nomor rekening tujuan.',
          'Masukkan jumlah uang yang ingin ditransfer.',
          'Cek data → pilih Benar / Ya.',
        ]],
        ['value' => 'Via Mobile Banking', 'title' => 'Via Mobile Banking', 'steps' => [
          'Buka aplikasi mobile banking Bukopin dan login.',
          'Pilih menu Transfer Dana / Transfer Antar Bank.',
          'Masukkan kode bank (441) + nomor rekening tujuan.',
          'Masukkan jumlah uang.',
          'Konfirmasi → masukkan PIN.',
        ]],
        ['value' => 'Via Cash', 'title' => 'Via Cash (Bayar di Tempat)', 'steps' => [
          'Datang ke kasir setelah reservasi.',
          'Sebutkan nama pemesan / bukti reservasi.',
          'Bayar sesuai nominal.',
          'Terima struk sebagai bukti.',
        ]],
      ] as $method)
        <div class="rounded-xl border border-base-200 bg-base-100">
          <label class="flex cursor-pointer items-center gap-3 px-4 py-3 text-sm font-semibold text-primary">
            <input type="radio" name="metode" value="{{ $method['value'] }}" class="radio radio-primary radio-sm payment-select">
            {{ $method['title'] }}
          </label>
          <div class="accordion-content border-t border-base-200 px-4 py-3">
            <ol class="list-decimal space-y-1 pl-5 text-sm text-base-content/70">
              @foreach($method['steps'] as $step)
                <li>{{ $step }}</li>
              @endforeach
            </ol>
          </div>
        </div>
      @endforeach
    </div>

    <div id="uploadSection" class="mt-5 hidden">
      <form action="{{ route('booking.payment.upload', $pengunjung->id) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
        @csrf
        <input type="hidden" name="metode_pembayaran" id="payment_method_upload">

        <label for="buktiFile" class="mb-1.5 block text-xs font-medium text-base-content/70">Upload Bukti Pembayaran</label>
        <input id="buktiFile" type="file" name="bukti_pembayaran" accept=".jpg,.jpeg,.png,.pdf" class="file-input file-input-bordered w-full" required>
        <p class="mt-1.5 text-xs text-base-content/50">Format: JPG, PNG, PDF (maks. 2MB).</p>

        <button type="submit" class="btn btn-primary mt-4 rounded-full normal-case">Kirim Bukti</button>
      </form>
    </div>

    <div id="cashButton" class="mt-5 hidden">
      <div class="mb-3 rounded-xl bg-secondary/10 px-4 py-3 text-xs text-base-content/70">
        Tidak perlu upload bukti. Admin akan memverifikasi jumlah uang dan mencetak invoice setelah konfirmasi.
      </div>
      <form action="{{ route('booking.payment.cash', $pengunjung->id) }}" method="POST">
        @csrf
        <input type="hidden" name="metode_pembayaran" value="Via Cash">
        <button type="submit" class="btn btn-primary w-full rounded-full normal-case">Konfirmasi Bayar di Tempat</button>
      </form>
    </div>
  </section>

  <aside class="soft-card h-fit p-6 sm:p-8">
    <span class="section-kicker">Ringkasan</span>
    <h2 class="mt-2 text-lg font-semibold text-primary">Booking Summary</h2>

    <div class="mt-4 grid gap-4 rounded-2xl border border-base-200 bg-base-100 p-4 sm:grid-cols-2">
      <div>
        <p class="text-[11px] font-semibold uppercase tracking-wider text-base-content/50">Check-in</p>
        <p class="mt-1 text-sm">{{ \Carbon\Carbon::parse($pengunjung->check_in)->translatedFormat('l, d F Y') }}</p>
        <p class="text-xs text-base-content/50">12.00 WIB</p>
      </div>
      <div>
        <p class="text-[11px] font-semibold uppercase tracking-wider text-base-content/50">Check-out</p>
        <p class="mt-1 text-sm">{{ \Carbon\Carbon::parse($pengunjung->check_out)->translatedFormat('l, d F Y') }}</p>
        <p class="text-xs text-base-content/50">12.00 WIB</p>
      </div>
      <div>
        <p class="text-[11px] font-semibold uppercase tracking-wider text-base-content/50">Kamar</p>
        <p class="mt-1 text-sm font-medium">{{ $kamar->jenis_kamar ?? '-' }}</p>
      </div>
      <div>
        <p class="text-[11px] font-semibold uppercase tracking-wider text-base-content/50">Reservasi</p>
        <p class="mt-1 text-sm font-medium">{{ $durasi ?? '-' }} hari</p>
      </div>
    </div>

    <h3 class="mt-6 text-sm font-semibold text-primary">Price Summary</h3>
    <div class="mt-3 flex items-start justify-between gap-4 text-sm">
      <span>Hunian ({{ $kamar->jenis_kamar ?? '-' }})</span>
      <span class="font-medium">Rp {{ number_format($kamar->harga ?? 0, 0, ',', '.') }}</span>
    </div>
    <p class="mt-2 text-xs leading-relaxed text-base-content/50">
      {{ $pengunjung->jumlah_kamar }} kamar × {{ $durasi }} hari × Rp {{ number_format($kamar->harga ?? 0, 0, ',', '.') }}
      = <strong class="text-base-content/70">Rp {{ number_format($totalKamar ?? 0, 0, ',', '.') }}</strong>
    </p>

    <div class="mt-5 flex items-center justify-between border-t border-base-200 pt-5">
      <span class="text-sm font-semibold">Total</span>
      <span class="text-xl font-semibold text-primary">Rp {{ number_format($totalPembayaran ?? 0, 0, ',', '.') }}</span>
    </div>

    <a href="{{ url('/') }}" class="btn btn-ghost mt-6 w-full rounded-full normal-case border border-base-300">
      <i class="fas fa-arrow-left text-xs"></i> Kembali ke Beranda
    </a>
  </aside>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.payment-select').forEach((radio) => {
    radio.addEventListener('change', function () {
      const upload = document.getElementById('uploadSection');
      const cashButton = document.getElementById('cashButton');

      document.querySelectorAll('.accordion-content').forEach((content) => content.classList.remove('show'));
      this.closest('.rounded-xl').querySelector('.accordion-content').classList.add('show');

      if (this.value === 'Via Cash') {
        upload.classList.add('hidden');
        cashButton.classList.remove('hidden');
      } else {
        upload.classList.remove('hidden');
        cashButton.classList.add('hidden');
        document.getElementById('payment_method_upload').value = this.value;
      }
    });
  });

  const form = document.getElementById('paymentForm');
  const fileInput = document.getElementById('buktiFile');

  form?.addEventListener('submit', (event) => {
    if (!fileInput.files.length) {
      event.preventDefault();
      Swal.fire({ icon: 'warning', title: 'Belum ada file', text: 'Silakan pilih bukti pembayaran.', confirmButtonColor: '#0d0b61' });
      return;
    }
    if (fileInput.files[0].size > 2 * 1024 * 1024) {
      event.preventDefault();
      Swal.fire({ icon: 'error', title: 'File terlalu besar', text: 'Ukuran maksimal 2MB.', confirmButtonColor: '#0d0b61' });
    }
  });
});
</script>
@endsection
