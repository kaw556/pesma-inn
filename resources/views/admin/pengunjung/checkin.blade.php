@extends('layout')

@section('content')
<div class="mx-auto max-w-2xl">
  <div class="mb-8">
    <span class="section-kicker">Data Pengunjung</span>
    <h1 class="section-heading mt-2">Check-In: {{ $p->nama }}</h1>
    <p class="mt-2 text-sm text-base-content/60">Booking #{{ $p->id }} · Check-in {{ $p->check_in }}</p>
  </div>

  <section class="soft-card p-6 sm:p-8">
    @if($errors->any())
      <div class="alert alert-error mb-6 text-sm">
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('pengunjung.checkin.process', $p->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
      @csrf

      <div class="grid gap-5 sm:grid-cols-2">
        <div>
          <span class="mb-1.5 block text-xs font-medium text-base-content/70">Nama Tamu</span>
          <input type="text" value="{{ $p->nama }}" readonly class="input input-bordered w-full bg-base-200/60">
        </div>
        <div>
          <span class="mb-1.5 block text-xs font-medium text-base-content/70">Jenis Tamu</span>
          <input type="text" value="{{ $p->jenis_tamu }}" readonly class="input input-bordered w-full bg-base-200/60">
        </div>
      </div>

      @if($p->jenis_tamu == 'Corporate')
        <div class="grid gap-5 sm:grid-cols-2">
          <div>
            <span class="mb-1.5 block text-xs font-medium text-base-content/70">Nama Kegiatan</span>
            <input type="text" value="{{ $p->nama_kegiatan }}" readonly class="input input-bordered w-full bg-base-200/60">
          </div>
          <div>
            <span class="mb-1.5 block text-xs font-medium text-base-content/70">PIC</span>
            <input type="text" value="{{ $p->nama_pic }} ({{ $p->no_telp_pic }})" readonly class="input input-bordered w-full bg-base-200/60">
          </div>
        </div>
        <div>
          <span class="mb-1.5 block text-xs font-medium text-base-content/70">Jumlah Peserta</span>
          <input type="text" value="{{ $p->jumlah_peserta }}" readonly class="input input-bordered w-full bg-base-200/60">
        </div>
      @endif

      <div>
        <label for="identity_type" class="mb-1.5 block text-xs font-medium text-base-content/70">Tipe Identitas <span class="text-error">*</span></label>
        <select id="identity_type" name="identity_type" required class="select select-bordered w-full">
          <option value="">-- Pilih Tipe Identitas --</option>
          <option value="KTP" {{ ($p->identity_type ?? '') == 'KTP' ? 'selected' : '' }}>KTP</option>
          <option value="KTM" {{ ($p->identity_type ?? '') == 'KTM' ? 'selected' : '' }}>KTM</option>
          <option value="SIM" {{ ($p->identity_type ?? '') == 'SIM' ? 'selected' : '' }}>SIM</option>
        </select>
        <p class="mt-1.5 text-xs text-base-content/50">Tamu wajib menyerahkan identitas asli (KTP/KTM/SIM).</p>
      </div>

      <div>
        <label for="bukti_identitas" class="mb-1.5 block text-xs font-medium text-base-content/70">Upload Foto Identitas <span class="text-error">*</span></label>
        <input id="bukti_identitas" type="file" name="bukti_identitas" accept=".jpg,.jpeg,.png,.pdf" required class="file-input file-input-bordered w-full">
        <p class="mt-1.5 text-xs text-base-content/50">Format: JPG, PNG, PDF (maks. 5MB).</p>
        @if($p->bukti_identitas)
          <a href="{{ Storage::url(str_replace('public/', '', $p->bukti_identitas)) }}" target="_blank" rel="noopener" class="mt-2 inline-block text-xs font-medium text-secondary hover:underline">
            Lihat identitas yang sudah diupload
          </a>
        @endif
      </div>

      <div class="alert alert-warning text-sm">
        <i class="fas fa-triangle-exclamation"></i>
        <span>Identitas asli akan disimpan admin dan dikembalikan saat check-out.</span>
      </div>

      <div class="flex flex-wrap gap-3 border-t border-base-200 pt-5">
        <button type="submit" class="btn btn-primary rounded-full normal-case">Proses Check-In</button>
        <a href="{{ route('pengunjung.show', $p->id) }}" class="btn btn-ghost rounded-full normal-case">Batal</a>
      </div>
    </form>
  </section>
</div>
@endsection
