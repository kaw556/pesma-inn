@extends('layouts.app')

@section('title', 'Booking Korporasi · Pesma Inn')

@section('content')
@php
  $fotoKamar = \App\Models\Kamar::fotoJenisPath($selectedRoom->jenis_kamar) ?? 'images/room.jpeg';
@endphp

<div class="mb-8">
  <span class="section-kicker">Pemesanan</span>
  <h1 class="section-heading mt-2">Booking Korporasi</h1>
  <p class="mt-2 text-sm text-base-content/60">Lengkapi data penanggung jawab kegiatan untuk melanjutkan ke pembayaran.</p>
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
  <span class="flex items-center gap-2 text-base-content/40">
    <span class="flex h-6 w-6 items-center justify-center rounded-full border border-base-300 text-[10px]">3</span>
    Payment
  </span>
</div>

<form action="{{ route('booking.corporate.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-[1.4fr_1fr]">
  @csrf
  <input type="hidden" name="jenis_tamu" value="corporate">

  <section class="soft-card p-6 sm:p-8">
    <h2 class="text-lg font-semibold text-primary">Data Penanggung Jawab</h2>

    <div class="mt-5 space-y-5">
      <div class="grid gap-5 sm:grid-cols-2">
        <div>
          <label for="nama_pic" class="mb-1.5 block text-xs font-medium text-base-content/70">Nama Lengkap PIC</label>
          <input id="nama_pic" type="text" name="nama_pic" value="{{ old('nama_pic') }}" required placeholder="Tulis nama lengkap PIC" class="input input-bordered w-full">
          @error('nama_pic') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="no_telp_pic" class="mb-1.5 block text-xs font-medium text-base-content/70">No Telepon PIC</label>
          <input id="no_telp_pic" type="text" name="no_telp_pic" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="{{ old('no_telp_pic') }}" required placeholder="Nomor WhatsApp" class="input input-bordered w-full">
          @error('no_telp_pic') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="grid gap-5 sm:grid-cols-2">
        <div>
          <label for="no_identitas" class="mb-1.5 block text-xs font-medium text-base-content/70">No Identitas PIC</label>
          <input id="no_identitas" type="text" name="no_identitas" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="{{ old('no_identitas') }}" required placeholder="KTP/Paspor" class="input input-bordered w-full">
          @error('no_identitas') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
        </div>
        <div>
          <label for="bukti_identitas" class="mb-1.5 block text-xs font-medium text-base-content/70">Upload Bukti Identitas <span class="text-error">*</span></label>
          <input id="bukti_identitas" type="file" name="bukti_identitas" accept=".jpg,.jpeg,.png" required class="file-input file-input-bordered w-full">
          @error('bukti_identitas') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
        </div>
      </div>

      <div>
        <label for="asal_persyarikatan" class="mb-1.5 block text-xs font-medium text-base-content/70">Nama Instansi / Persyarikatan</label>
        <input id="asal_persyarikatan" type="text" name="asal_persyarikatan" value="{{ old('asal_persyarikatan') }}" placeholder="Contoh: UMS, Muhammadiyah..." class="input input-bordered w-full">
      </div>

      <div class="grid gap-5 sm:grid-cols-2">
        <div>
          <label for="nama_kegiatan" class="mb-1.5 block text-xs font-medium text-base-content/70">Nama Kegiatan</label>
          <input id="nama_kegiatan" type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" placeholder="Contoh: Workshop..." class="input input-bordered w-full">
        </div>
        <div>
          <label for="tanggal_persyarikatan" class="mb-1.5 block text-xs font-medium text-base-content/70">Tanggal Kegiatan</label>
          <input id="tanggal_persyarikatan" type="date" name="tanggal_persyarikatan" value="{{ old('tanggal_persyarikatan') }}" class="input input-bordered w-full">
        </div>
      </div>

      <div>
        <label for="jumlah_peserta" class="mb-1.5 block text-xs font-medium text-base-content/70">Jumlah Peserta</label>
        <input id="jumlah_peserta" type="number" name="jumlah_peserta" min="1" value="{{ old('jumlah_peserta') }}" placeholder="Total orang" required class="input input-bordered w-full">
        @error('jumlah_peserta') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
      </div>

      <div>
        <label for="special_request" class="mb-1.5 block text-xs font-medium text-base-content/70">Special Request</label>
        <textarea id="special_request" name="special_request" rows="3" placeholder="Tulis permintaan khusus (opsional)" class="textarea textarea-bordered w-full">{{ old('special_request') }}</textarea>
      </div>

      <div class="flex flex-wrap gap-3 border-t border-base-200 pt-5">
        <button type="submit" class="btn btn-primary rounded-full normal-case">Book Now <i class="fas fa-arrow-right text-xs"></i></button>
        <a href="{{ url()->previous() }}" class="btn btn-ghost rounded-full normal-case border border-base-300">Batal</a>
      </div>
    </div>
  </section>

  <aside class="soft-card h-fit p-6 sm:p-8">
    <span class="section-kicker">Ringkasan Kamar</span>

    <img src="{{ asset($fotoKamar) }}" alt="{{ $selectedRoom->jenis_kamar }}" class="mt-4 h-52 w-full rounded-2xl object-cover">

    <div class="mt-4 flex items-start justify-between gap-4">
      <div>
        <h2 class="text-lg font-semibold text-primary">{{ $selectedRoom->jenis_kamar }}</h2>
        <p class="mt-1 text-xs text-base-content/50">{{ $selectedRoom->kode_kamar }}</p>
      </div>
      <p class="shrink-0 text-sm font-semibold text-secondary">Rp {{ number_format($selectedRoom->harga, 0, ',', '.') }}<span class="font-normal text-base-content/50"> /malam</span></p>
    </div>

    <input type="hidden" name="kode_kamar" value="{{ $selectedRoom->kode_kamar }}">
    <input type="hidden" name="jumlah_kamar" value="{{ $jumlah_kamar ?? 1 }}">

    <div class="mt-5 flex items-center gap-2 rounded-xl bg-secondary/10 px-4 py-3 text-sm">
      <i class="fas fa-building text-secondary"></i>
      <span>Memesan <strong>{{ $jumlah_kamar ?? 1 }} kamar</strong></span>
    </div>

    <div class="mt-5 grid gap-4 border-t border-base-200 pt-5 sm:grid-cols-2">
      <div>
        <p class="text-[11px] font-semibold uppercase tracking-wider text-base-content/50">Check-in</p>
        <input type="date" name="check_in" id="check_in" value="{{ old('check_in', $checkin) }}" required readonly class="input input-bordered mt-1.5 w-full bg-base-200/60">
        @error('check_in') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
      </div>
      <div>
        <p class="text-[11px] font-semibold uppercase tracking-wider text-base-content/50">Check-out</p>
        <input type="date" name="check_out" id="check_out" value="{{ old('check_out', $checkout) }}" required readonly class="input input-bordered mt-1.5 w-full bg-base-200/60">
        @error('check_out') <p class="mt-1 text-xs text-error">{{ $message }}</p> @enderror
      </div>
    </div>
    <p class="mt-2 text-xs text-base-content/50">Tanggal sesuai pilihan di halaman beranda.</p>
  </aside>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const checkIn = document.getElementById('check_in');
  const checkOut = document.getElementById('check_out');
  const form = document.querySelector('form');

  form?.addEventListener('submit', function (event) {
    const pic = document.querySelector('[name="nama_pic"]')?.value.trim();
    const peserta = document.querySelector('[name="jumlah_peserta"]')?.value.trim();
    const hasProof = document.querySelector('[name="bukti_identitas"]')?.files.length > 0;

    if (!pic || !peserta || !hasProof) {
      event.preventDefault();
      Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Lengkapi data wajib (Nama PIC, Jumlah Peserta, Bukti Identitas).', confirmButtonColor: '#0d0b61' });
      return;
    }

    if (new Date(checkOut.value) <= new Date(checkIn.value)) {
      event.preventDefault();
      Swal.fire({ icon: 'error', title: 'Tanggal Tidak Valid', text: 'Check-out harus setelah check-in.', confirmButtonColor: '#0d0b61' });
    }
  });
});
</script>
@endsection
