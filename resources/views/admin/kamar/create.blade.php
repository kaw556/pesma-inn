@extends('layout')

@section('content')
<div class="mx-auto max-w-2xl">
  <div class="mb-8">
    <span class="section-kicker">Data Kamar</span>
    <h1 class="section-heading mt-2">Tambah Kamar Baru</h1>
    <p class="mt-2 text-sm text-base-content/60">Isi detail kamar untuk menambahkannya ke daftar penginapan.</p>
  </div>

  <section class="soft-card p-6 sm:p-8">
    <form action="{{ route('kamar.store') }}" method="POST" class="space-y-5">
      @csrf

      <div>
        <label for="kode_kamar" class="mb-1.5 block text-xs font-medium text-base-content/70">Kode / Nomor Kamar</label>
        <input id="kode_kamar" name="kode_kamar" required class="input input-bordered w-full" placeholder="KUN-STD-01">
      </div>

      <div class="grid gap-5 sm:grid-cols-2">
        <div>
          <label for="jenis_kamar" class="mb-1.5 block text-xs font-medium text-base-content/70">Jenis Kamar</label>
          <input id="jenis_kamar" name="jenis_kamar" required class="input input-bordered w-full" placeholder="Standard">
        </div>
        <div>
          <label for="gedung" class="mb-1.5 block text-xs font-medium text-base-content/70">Gedung</label>
          <input id="gedung" name="gedung" class="input input-bordered w-full" placeholder="Gedung A">
        </div>
      </div>

      <div>
        <label for="harga" class="mb-1.5 block text-xs font-medium text-base-content/70">Harga (Rp)</label>
        <input id="harga" name="harga" type="number" min="0" class="input input-bordered w-full" placeholder="250000">
      </div>

      <div>
        <label for="fasilitas" class="mb-1.5 block text-xs font-medium text-base-content/70">Fasilitas</label>
        <input id="fasilitas" name="fasilitas" class="input input-bordered w-full" placeholder="AC, Kasur, Kamar mandi">
      </div>

      <div>
        <label for="status" class="mb-1.5 block text-xs font-medium text-base-content/70">Status</label>
        <select id="status" name="status" class="select select-bordered w-full">
          <option value="kosong">Kosong</option>
          <option value="terisi">Terisi</option>
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
