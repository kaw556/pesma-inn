@extends('layout')

@section('content')
<div class="mb-8">
  <span class="section-kicker">Data Kamar</span>
  <div class="mt-2 flex flex-wrap items-end justify-between gap-4">
    <div>
      <h1 class="section-heading">Daftar Kamar Penginapan</h1>
      <p class="mt-2 text-sm text-base-content/60">Harga dan foto dikelola per jenis kamar. Tabel di bawah hanya untuk melihat data tiap kamar.</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <button type="button" id="toggleHargaFoto" class="btn btn-ghost btn-sm rounded-full normal-case border border-base-300">
        <i class="fas fa-pen text-xs"></i> <span id="toggleHargaFotoLabel">Edit</span>
      </button>
      <a href="{{ route('kamar.create') }}" class="btn btn-primary btn-sm rounded-full normal-case">
        <i class="fas fa-plus text-xs"></i> Tambah Kamar
      </a>
    </div>
  </div>
</div>

<section id="hargaFotoCard" class="soft-card p-5 sm:p-6 {{ $errors->any() || session('error') ? '' : 'hidden' }}">
  <span class="section-kicker">Harga & Foto</span>
  <h2 class="mt-2 text-lg font-semibold text-primary">Per Jenis Kamar</h2>
  <p class="mt-1 text-sm text-base-content/60">
    Perubahan harga berlaku untuk semua kamar dengan jenis yang sama. Foto JPG/JPEG maks. 4MB akan mengganti gambar di beranda.
  </p>

  <div class="mt-5 divide-y divide-base-200">
    @forelse($jenisKamars as $item)
      <div class="flex flex-wrap items-center gap-4 py-4">
        <div class="h-16 w-24 shrink-0 overflow-hidden rounded-xl border border-base-200 bg-base-200/50">
          @if($item['foto'])
            <img src="{{ asset($item['foto']) }}" alt="{{ $item['jenis'] }}" class="h-full w-full object-cover">
          @else
            <div class="flex h-full items-center justify-center text-base-content/30">
              <i class="fas fa-image text-xl"></i>
            </div>
          @endif
        </div>

        <div class="min-w-[140px] flex-1">
          <p class="text-sm font-semibold text-primary">{{ $item['jenis'] }}</p>
          <p class="mt-0.5 text-xs text-base-content/50">
            {{ $kamars->where('jenis_kamar', $item['jenis'])->count() }} kamar ·
            {{ $item['foto'] ? basename($item['foto']) : 'Belum ada gambar' }}
          </p>
        </div>

        <form action="{{ route('kamar.jenis.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-2">
          @csrf
          <input type="hidden" name="jenis_kamar" value="{{ $item['jenis'] }}">

          <label class="block">
            <span class="mb-1 block text-[11px] font-medium text-base-content/60">Harga (Rp)</span>
            <input type="number" name="harga" value="{{ $item['harga'] }}" min="0" required class="input input-bordered input-sm w-36">
          </label>

          <label class="block">
            <span class="mb-1 block text-[11px] font-medium text-base-content/60">Foto Baru</span>
            <input type="file" name="foto" accept=".jpg,.jpeg" class="file-input file-input-bordered file-input-sm w-full sm:w-48">
          </label>

          <button type="submit" class="btn btn-primary btn-sm rounded-full normal-case">
            <i class="fas fa-floppy-disk text-xs"></i> Simpan
          </button>
        </form>
      </div>
    @empty
      <p class="py-6 text-sm text-base-content/50">Belum ada jenis kamar terdaftar.</p>
    @endforelse
  </div>
</section>

<section class="soft-card mt-6 p-5 sm:p-6">
  <div class="flex flex-wrap items-center gap-3">
    <label class="relative min-w-[220px] flex-1">
      <i class="fas fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-xs text-base-content/40"></i>
      <input id="kamar-filter" type="search" placeholder="Cari kode kamar, mis. KUN-STD-01" class="input input-bordered w-full pl-11">
    </label>
    <button type="button" id="kamar-filter-action" class="btn btn-primary btn-sm rounded-full normal-case">Cari</button>
    <span id="kamar-search-result" class="text-xs text-secondary"></span>
  </div>

  <div class="mt-5 overflow-x-auto">
    <table class="table w-full">
      <thead class="bg-primary/5 text-primary">
        <tr>
          <th class="text-[11px] uppercase tracking-wider">Kode Kamar</th>
          <th class="text-[11px] uppercase tracking-wider">Jenis Kamar</th>
          <th class="text-[11px] uppercase tracking-wider">Gedung</th>
          <th class="text-[11px] uppercase tracking-wider">Harga</th>
          <th class="text-[11px] uppercase tracking-wider">Fasilitas</th>
          <th class="text-[11px] uppercase tracking-wider">Status</th>
          <th class="text-[11px] uppercase tracking-wider">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($kamars as $kamar)
          <tr class="hover:bg-base-200/50" data-status="{{ $kamar->status ?? 'kosong' }}">
            <td class="text-sm font-medium">{{ $kamar->kode_kamar ?? $kamar->nomor_kamar }}</td>
            <td class="text-sm">{{ $kamar->jenis_kamar }}</td>
            <td class="text-sm">{{ $kamar->gedung }}</td>
            <td class="text-sm font-semibold text-primary">Rp {{ number_format($kamar->harga ?? 0, 0, ',', '.') }}</td>
            <td class="max-w-[200px] truncate text-xs text-base-content/60">{{ $kamar->fasilitas }}</td>
            <td>
              @if(($kamar->status ?? 'kosong') == 'kosong')
                <span class="inline-flex rounded-full bg-success/10 px-3 py-1 text-xs font-medium text-success">Kosong</span>
              @else
                <span class="inline-flex rounded-full bg-error/10 px-3 py-1 text-xs font-medium text-error">Terisi</span>
              @endif
            </td>
            <td>
              <details class="dropdown dropdown-end">
                <summary class="btn btn-ghost btn-sm rounded-full normal-case text-secondary hover:bg-secondary/10">Aksi</summary>
                <ul class="menu dropdown-content z-20 mt-2 w-44 rounded-box border border-base-200 bg-base-100 p-2 shadow-xl">
                  <li><a href="{{ route('kamar.edit', $kamar->id) }}">Edit Data</a></li>
                  <li><a href="#" onclick="alert('View Detail Kamar: {{ $kamar->kode_kamar ?? $kamar->nomor_kamar }}')">View Detail</a></li>
                  <li>
                    <form action="{{ route('kamar.destroy', $kamar->id) }}" method="GET" onsubmit="return confirm('Hapus kamar ini?')">
                      <button type="submit" class="text-error">Hapus</button>
                    </form>
                  </li>
                </ul>
              </details>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="py-10 text-center text-sm text-base-content/50">Belum ada data kamar.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('kamar-filter');
  const actionBtn = document.getElementById('kamar-filter-action');
  const resultArea = document.getElementById('kamar-search-result');
  const table = document.querySelector('.table');
  if (!input || !table) return;

  function reorderRows() {
    const tbody = table.tBodies[0];
    if (!tbody) return;
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const visible = rows.filter((row) => row.style.display !== 'none');
    const hidden = rows.filter((row) => row.style.display === 'none');
    visible.sort((a, b) => {
      const pa = (a.dataset.status || '').toLowerCase() === 'kosong' ? 1 : 0;
      const pb = (b.dataset.status || '').toLowerCase() === 'kosong' ? 1 : 0;
      return pa - pb;
    });
    visible.concat(hidden).forEach((row) => tbody.appendChild(row));
  }

  function filter() {
    const query = (input.value || '').trim().toLowerCase();
    table.querySelectorAll('tbody tr').forEach((row) => {
      const code = row.querySelector('td')?.textContent.trim().toLowerCase() || '';
      row.style.display = !query || code.includes(query) ? '' : 'none';
    });
    reorderRows();
  }

  input.addEventListener('input', filter);
  actionBtn?.addEventListener('click', () => {
    filter();
    const query = (input.value || '').trim();
    resultArea.textContent = query ? 'Menampilkan hasil: ' + query : '';
  });

  reorderRows();

  const editButton = document.getElementById('toggleHargaFoto');
  const editLabel = document.getElementById('toggleHargaFotoLabel');
  const hargaFotoCard = document.getElementById('hargaFotoCard');

  editButton?.addEventListener('click', () => {
    const willShow = hargaFotoCard.classList.contains('hidden');
    hargaFotoCard.classList.toggle('hidden', !willShow);
    editLabel.textContent = willShow ? 'Tutup Edit' : 'Edit';
    if (willShow) hargaFotoCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });
});
</script>
@endsection
