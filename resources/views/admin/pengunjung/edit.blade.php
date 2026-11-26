@extends('layout')

@section('content')
<div class="mx-auto max-w-4xl">
  <div class="mb-8">
    <span class="section-kicker">Data Pengunjung</span>
    <h1 class="section-heading mt-2">Edit Data Pengunjung</h1>
    <p class="mt-2 text-sm text-base-content/60">{{ $p->nama }}</p>
  </div>

  @if($errors->any())
    <div class="alert alert-error mb-6 text-sm">
      <ul class="list-disc pl-5">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <section class="soft-card p-6 sm:p-8">
    <form action="{{ route('pengunjung.update', $p->id) }}" method="POST" class="space-y-5" id="editForm">
      @csrf

      <div class="grid gap-5 sm:grid-cols-2">
        <div>
          <label for="nama_pic" class="mb-1.5 block text-xs font-medium text-base-content/70">Nama PIC <span class="text-error">*</span></label>
          <input id="nama_pic" type="text" name="nama_pic" value="{{ old('nama_pic', $p->nama_pic ?: $p->nama) }}" required class="input input-bordered w-full">
          <input type="hidden" name="nama" value="{{ $p->nama_pic ?: $p->nama }}">
        </div>
        <div>
          <label for="jenis_tamu" class="mb-1.5 block text-xs font-medium text-base-content/70">Jenis Tamu</label>
          <select id="jenis_tamu" name="jenis_tamu" class="select select-bordered w-full">
            <option value="corporate" {{ old('jenis_tamu', $p->jenis_tamu) == 'corporate' ? 'selected' : '' }}>Corporate</option>
            <option value="individu" {{ old('jenis_tamu', $p->jenis_tamu) == 'individu' ? 'selected' : '' }}>Individu</option>
          </select>
        </div>
      </div>

      <div class="grid gap-5 sm:grid-cols-2">
        <div>
          <label for="no_identitas" class="mb-1.5 block text-xs font-medium text-base-content/70">No Identitas</label>
          <input id="no_identitas" type="text" name="no_identitas" value="{{ old('no_identitas', $p->no_identitas) }}" class="input input-bordered w-full">
        </div>
        <div>
          <label for="no_telp_pic" class="mb-1.5 block text-xs font-medium text-base-content/70">No HP PIC</label>
          <input id="no_telp_pic" type="text" name="no_telp_pic" value="{{ old('no_telp_pic', $p->no_telp_pic ?: $p->no_telp) }}" class="input input-bordered w-full">
          <input type="hidden" name="no_telp" value="{{ $p->no_telp_pic ?: $p->no_telp }}">
        </div>
      </div>

      <div class="grid gap-5 sm:grid-cols-2">
        <div>
          <label for="check_in" class="mb-1.5 block text-xs font-medium text-base-content/70">Check-in <span class="text-error">*</span></label>
          <input id="check_in" type="date" name="check_in" value="{{ old('check_in', $p->check_in) }}" required class="input input-bordered w-full">
        </div>
        <div>
          <label for="check_out" class="mb-1.5 block text-xs font-medium text-base-content/70">Check-out <span class="text-error">*</span></label>
          <input id="check_out" type="date" name="check_out" value="{{ old('check_out', $p->check_out) }}" required class="input input-bordered w-full">
        </div>
      </div>

      @php
        $selectedRooms = $p->kode_kamar ? explode(',', str_replace(' ', '', $p->kode_kamar)) : [];
      @endphp

      <div>
        <span class="mb-1.5 block text-xs font-medium text-base-content/70">Kode Kamar</span>

        <details id="roomDropdown" class="dropdown w-full">
          <summary class="select select-bordered flex w-full items-center justify-between gap-2 font-normal">
            <span id="roomSummary">Pilih kamar…</span>
            <i class="fas fa-chevron-down text-xs opacity-50"></i>
          </summary>

          <div class="dropdown-content z-20 mt-2 w-full rounded-box border border-base-200 bg-base-100 p-3 shadow-xl">
            <input id="roomSearch" type="search" placeholder="Cari kode / jenis kamar" class="input input-bordered input-sm w-full">

            <div id="roomList" class="mt-2 max-h-64 space-y-1 overflow-y-auto">
              @foreach($kamars as $room)
                @php
                  $isSelected = in_array($room->kode_kamar, $selectedRooms);
                  $isAvailable = $room->status === 'kosong' || $isSelected;
                @endphp
                <label class="room-option flex cursor-pointer items-center gap-3 rounded-lg px-2 py-2 text-sm hover:bg-base-200/60 {{ $isAvailable ? '' : 'opacity-50' }}">
                  <input type="checkbox" name="kode_kamar[]" value="{{ $room->kode_kamar }}" class="checkbox checkbox-primary checkbox-sm" {{ $isSelected ? 'checked' : '' }} {{ $isAvailable ? '' : 'disabled' }}>
                  <span class="flex-1">
                    <span class="font-medium">{{ $room->kode_kamar }}</span>
                    <span class="text-xs text-base-content/50">— {{ $room->jenis_kamar }} · {{ $room->gedung }}</span>
                  </span>
                  @if(!$isAvailable)
                    <span class="text-[10px] text-error">Terisi</span>
                  @endif
                </label>
              @endforeach
            </div>
          </div>
        </details>

        <div id="selectedRooms" class="mt-2 flex flex-wrap gap-2"></div>
        <p class="mt-1.5 text-xs text-base-content/50">
          Klik dropdown untuk memilih beberapa kamar.
          <span class="font-semibold text-success">{{ $kamars->where('status', 'kosong')->count() }}</span> kamar kosong tersedia.
        </p>
      </div>

      <div class="grid gap-5 sm:grid-cols-2">
        <div>
          <label for="jumlah_kamar" class="mb-1.5 block text-xs font-medium text-base-content/70">Jumlah Kamar</label>
          <input id="jumlah_kamar" type="number" name="jumlah_kamar" value="{{ old('jumlah_kamar', $p->jumlah_kamar) }}" min="1" class="input input-bordered w-full">
        </div>
        <div>
          <label for="payment_status" class="mb-1.5 block text-xs font-medium text-base-content/70">Status Pembayaran</label>
          <select id="payment_status" name="payment_status" class="select select-bordered w-full">
            <option value="pending" {{ old('payment_status', $p->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="konfirmasi_booking" {{ old('payment_status', $p->payment_status) == 'konfirmasi_booking' ? 'selected' : '' }}>Konfirmasi Booking</option>
            <option value="paid" {{ old('payment_status', $p->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="lunas" {{ old('payment_status', $p->payment_status) == 'lunas' ? 'selected' : '' }}>Lunas</option>
            <option value="rejected" {{ old('payment_status', $p->payment_status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
          </select>
        </div>
      </div>

      <div class="border-t border-base-200 pt-5">
        <h2 class="text-sm font-semibold text-primary">Data Kegiatan</h2>
        <div class="mt-4">
          <label for="nama_kegiatan" class="mb-1.5 block text-xs font-medium text-base-content/70">Nama Kegiatan</label>
          <input id="nama_kegiatan" type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $p->nama_kegiatan) }}" class="input input-bordered w-full">
        </div>
      </div>

      <div class="flex flex-wrap justify-end gap-3 border-t border-base-200 pt-5">
        <a href="{{ route('pengunjung.show', $p->id) }}" class="btn btn-ghost rounded-full normal-case">Batal</a>
        <button type="submit" class="btn btn-primary rounded-full normal-case">Simpan Perubahan</button>
      </div>
    </form>
  </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const summary = document.getElementById('roomSummary');
  const chips = document.getElementById('selectedRooms');
  const search = document.getElementById('roomSearch');
  const checkboxes = Array.from(document.querySelectorAll('#roomList input[name="kode_kamar[]"]'));

  function render() {
    const selected = checkboxes.filter((box) => box.checked);

    summary.textContent = selected.length
      ? `${selected.length} kamar dipilih`
      : 'Pilih kamar…';

    chips.innerHTML = '';
    selected.forEach((box) => {
      const chip = document.createElement('button');
      chip.type = 'button';
      chip.className = 'inline-flex items-center gap-1 rounded-full bg-secondary/10 px-3 py-1 text-xs font-medium text-secondary';
      chip.textContent = box.value + ' ✕';
      chip.addEventListener('click', () => {
        box.checked = false;
        render();
      });
      chips.appendChild(chip);
    });
  }

  checkboxes.forEach((box) => box.addEventListener('change', render));

  search?.addEventListener('input', () => {
    const query = search.value.trim().toLowerCase();
    document.querySelectorAll('.room-option').forEach((option) => {
      option.classList.toggle('hidden', !option.textContent.toLowerCase().includes(query));
    });
  });

  render();
});
</script>
@endsection
