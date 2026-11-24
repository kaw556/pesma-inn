@extends('layout')

@section('content')
<div class="mx-auto max-w-2xl">
  <div class="mb-8">
    <span class="section-kicker">Data Kamar</span>
    <h1 class="section-heading mt-2">Edit Kamar</h1>
    <p class="mt-2 text-sm text-base-content/60">Perbarui detail kamar {{ $kamar->kode_kamar ?? $kamar->nomor_kamar }}.</p>
  </div>

  <section class="soft-card p-6 sm:p-8">
    <form action="{{ route('kamar.update', $kamar->id) }}" method="POST" class="space-y-5">
      @csrf

      <div>
        <label for="kode_kamar" class="mb-1.5 block text-xs font-medium text-base-content/70">Kode / Nomor Kamar</label>
        <input id="kode_kamar" name="kode_kamar" value="{{ $kamar->kode_kamar ?? $kamar->nomor_kamar }}" readonly class="input input-bordered w-full bg-base-200/60">
      </div>

      <div class="grid gap-5 sm:grid-cols-2">
        <div>
          <label for="jenis_kamar" class="mb-1.5 block text-xs font-medium text-base-content/70">Jenis Kamar</label>
          <input id="jenis_kamar" name="jenis_kamar" value="{{ $kamar->jenis_kamar }}" required class="input input-bordered w-full">
        </div>
        <div>
          <label for="gedung" class="mb-1.5 block text-xs font-medium text-base-content/70">Gedung</label>
          <input id="gedung" name="gedung" value="{{ $kamar->gedung }}" class="input input-bordered w-full">
        </div>
      </div>

      <div>
        <label for="harga" class="mb-1.5 block text-xs font-medium text-base-content/70">Harga (Rp)</label>
        <input id="harga" type="number" value="{{ $kamar->harga }}" readonly class="input input-bordered w-full bg-base-200/60">
        <p class="mt-1.5 text-xs text-base-content/50">Harga diatur per jenis kamar lewat kartu di bawah atau halaman Daftar Kamar.</p>
      </div>

      <div>
        <label for="fasilitas" class="mb-1.5 block text-xs font-medium text-base-content/70">Fasilitas</label>
        <input id="fasilitas" name="fasilitas" value="{{ $kamar->fasilitas }}" class="input input-bordered w-full">
      </div>

      <div>
        <label for="status" class="mb-1.5 block text-xs font-medium text-base-content/70">Status</label>
        <select id="status" name="status" class="select select-bordered w-full">
          <option value="kosong" {{ $kamar->status == 'kosong' ? 'selected' : '' }}>Kosong</option>
          <option value="terisi" {{ $kamar->status == 'terisi' ? 'selected' : '' }}>Terisi</option>
        </select>
      </div>

      <div class="flex justify-end gap-3 border-t border-base-200 pt-5">
        <a href="{{ route('kamar.index') }}" class="btn btn-ghost rounded-full normal-case">Batal</a>
        <button type="submit" class="btn btn-primary rounded-full normal-case">Simpan</button>
      </div>
    </form>
  </section>
</div>
@endsection
