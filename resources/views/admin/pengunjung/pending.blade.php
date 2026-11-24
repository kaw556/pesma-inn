@extends('layout')

@section('content')
<div class="mb-8">
  <span class="section-kicker">Data Pengunjung</span>
  <div class="mt-2 flex flex-wrap items-end justify-between gap-4">
    <div>
      <h1 class="section-heading">Konfirmasi Pembayaran & Booking</h1>
      <p class="mt-2 text-sm text-base-content/60">Tinjau booking yang masih pending, upload bukti bayar, lalu setujui dan tentukan kamar.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost btn-sm rounded-full normal-case border border-base-300">
      <i class="fas fa-arrow-left text-xs"></i> Kembali
    </a>
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
  <div class="overflow-x-auto">
    <table class="table w-full">
      <thead class="bg-primary/5 text-primary">
        <tr>
          <th class="text-[11px] uppercase tracking-wider">#</th>
          <th class="text-[11px] uppercase tracking-wider">Nama / Jenis</th>
          <th class="text-[11px] uppercase tracking-wider">Identitas / PIC</th>
          <th class="text-[11px] uppercase tracking-wider">Tanggal Booking</th>
          <th class="text-[11px] uppercase tracking-wider">Jml Kamar</th>
          <th class="text-[11px] uppercase tracking-wider">Status</th>
          <th class="text-[11px] uppercase tracking-wider">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pengunjungs as $guest)
          @php
            $phone = preg_replace('/[^0-9+]/', '', $guest->no_telp_pic ?? '');
            $wa = $phone ? 'https://wa.me/' . ltrim($phone, '0') : null;
            $proofUrl = $guest->bukti_pembayaran ? \Illuminate\Support\Facades\Storage::url($guest->bukti_pembayaran) : null;
            $isPdf = $guest->bukti_pembayaran && strtolower(pathinfo($guest->bukti_pembayaran, PATHINFO_EXTENSION)) === 'pdf';
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
              <span class="mt-1 inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-medium {{ strtolower($guest->jenis_tamu) == 'corporate' ? 'bg-success/10 text-success' : 'bg-info/10 text-info' }}">
                {{ ucfirst($guest->jenis_tamu) }}
              </span>
            </td>
            <td class="text-xs">{{ strtolower($guest->jenis_tamu) == 'corporate' ? ($guest->no_telp_pic ?? '-') : ($guest->no_identitas ?? '-') }}</td>
            <td class="text-xs">{{ $guest->check_in }} → {{ $guest->check_out }}</td>
            <td class="text-sm font-medium">{{ $guest->jumlah_kamar ?? '-' }}</td>
            <td>
              <span class="inline-flex rounded-full bg-warning/15 px-3 py-1 text-xs font-medium text-warning">{{ $guest->payment_status_label }}</span>
            </td>
            <td>
              <div class="flex flex-wrap gap-2">
                @if($wa)
                  <a href="{{ $wa }}" target="_blank" rel="noopener" class="btn btn-ghost btn-xs rounded-full normal-case text-[#25D366] hover:bg-[#25D366]/10" aria-label="Chat WhatsApp">
                    <i class="fab fa-whatsapp text-sm"></i>
                  </a>
                @endif
                <form action="{{ route('pengunjung.reject', $guest->id) }}" method="POST" class="reject-form">
                  @csrf
                  <button type="submit" class="btn btn-error btn-xs rounded-full normal-case">Tolak</button>
                </form>
                <form action="{{ route('pengunjung.approve', $guest->id) }}" method="POST" class="approve-form">
                  @csrf
                  <button type="submit" class="btn btn-primary btn-xs rounded-full normal-case">Konfirmasi</button>
                </form>
              </div>
            </td>
          </tr>

          <tr class="detail-row">
            <td colspan="7" class="bg-base-200/40 p-5">
              <div class="grid gap-5 lg:grid-cols-3">
                <div class="rounded-2xl border border-base-200 bg-base-100 p-5">
                  <h3 class="text-xs font-semibold uppercase tracking-wider text-secondary">Detail Booking</h3>
                  <dl class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between gap-3"><dt class="text-base-content/60">Nama</dt><dd class="text-right font-medium">{{ $guest->nama }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-base-content/60">Jenis Tamu</dt><dd class="text-right font-medium">{{ $guest->jenis_tamu }}</dd></div>
                    @if(strtolower($guest->jenis_tamu) == 'corporate')
                      <div class="flex justify-between gap-3"><dt class="text-base-content/60">Kegiatan</dt><dd class="text-right font-medium">{{ $guest->nama_kegiatan ?? '-' }}</dd></div>
                      <div class="flex justify-between gap-3"><dt class="text-base-content/60">PIC</dt><dd class="text-right font-medium">{{ $guest->nama_pic ?? '-' }} ({{ $guest->no_telp_pic ?? '-' }})</dd></div>
                    @else
                      <div class="flex justify-between gap-3"><dt class="text-base-content/60">No Identitas</dt><dd class="text-right font-medium">{{ $guest->no_identitas }}</dd></div>
                      <div class="flex justify-between gap-3"><dt class="text-base-content/60">No Telp</dt><dd class="text-right font-medium">{{ $guest->no_telp }}</dd></div>
                    @endif
                    <div class="flex justify-between gap-3"><dt class="text-base-content/60">Check-in</dt><dd class="text-right font-medium">{{ $guest->check_in }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-base-content/60">Check-out</dt><dd class="text-right font-medium">{{ $guest->check_out }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-base-content/60">Jml Kamar</dt><dd class="text-right font-medium">{{ $guest->jumlah_kamar ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-base-content/60">Kamar</dt><dd class="text-right font-medium">{{ $guest->kode_kamar ?? 'Belum di-assign' }}</dd></div>
                  </dl>
                </div>

                <div class="rounded-2xl border border-base-200 bg-base-100 p-5">
                  <h3 class="text-xs font-semibold uppercase tracking-wider text-secondary">Upload Bukti Manual</h3>

                  @if($guest->bukti_pembayaran)
                    <div class="mt-3 rounded-xl border border-success/30 bg-success/5 p-4">
                      <p class="text-xs font-semibold text-success">✓ Bukti pembayaran tersimpan</p>
                      <div class="mt-3 flex justify-center rounded-lg bg-base-100 p-3">
                        @if($isPdf)
                          <div class="text-center text-base-content/50">
                            <i class="fas fa-file-pdf text-3xl"></i>
                            <p class="mt-1 text-xs">File PDF</p>
                          </div>
                        @else
                          <img src="{{ $proofUrl }}" alt="Bukti Pembayaran" class="max-h-44 rounded-lg object-contain" onerror="this.style.display='none'">
                        @endif
                      </div>
                      <a href="{{ $proofUrl }}" target="_blank" rel="noopener" class="btn btn-ghost btn-xs mt-3 w-full rounded-full normal-case text-secondary">
                        <i class="fas fa-up-right-from-square text-[10px]"></i> Lihat bukti
                      </a>
                    </div>
                  @endif

                  <form action="{{ route('pengunjung.upload_payment', $guest->id) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3">
                    @csrf
                    <label for="bukti_file_{{ $guest->id }}" class="mb-1.5 block text-xs font-medium text-base-content/70">
                      {{ $guest->bukti_pembayaran ? 'Ganti dengan file baru:' : 'Pilih file bukti pembayaran:' }}
                    </label>
                    <input type="file" name="bukti_pembayaran" id="bukti_file_{{ $guest->id }}" accept=".jpg,.jpeg,.png,.pdf" required class="file-input file-input-bordered w-full" onchange="previewFile({{ $guest->id }})">
                    <p class="text-[11px] text-base-content/50">Format: JPG, PNG, PDF (maks. 5MB).</p>

                    <div id="preview_{{ $guest->id }}" class="hidden rounded-xl border border-base-200 bg-base-200/50 p-3">
                      <p class="mb-2 text-[11px] text-base-content/50">Preview</p>
                      <img id="preview_img_{{ $guest->id }}" class="mx-auto max-h-36 rounded-lg object-contain" alt="Preview bukti">
                      <p id="preview_pdf_{{ $guest->id }}" class="hidden text-center text-xs text-base-content/60">
                        <i class="fas fa-file-pdf text-2xl"></i>
                        <span id="preview_filename_{{ $guest->id }}" class="mt-1 block"></span>
                      </p>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm rounded-full normal-case">
                      {{ $guest->bukti_pembayaran ? 'Ganti File' : 'Upload File' }}
                    </button>
                  </form>
                </div>

                <div class="rounded-2xl border border-base-200 bg-base-100 p-5">
                  <h3 class="text-xs font-semibold uppercase tracking-wider text-secondary">Approve & Assign Kamar</h3>
                  <form action="{{ route('pengunjung.approve', $guest->id) }}" method="POST" class="mt-3 space-y-3">
                    @csrf
                    <label for="assign_kamar_select_{{ $guest->id }}" class="mb-1.5 block text-xs font-medium text-base-content/70">Pilih kamar (Ctrl/Shift untuk multi-select)</label>
                    <select name="assign_kamar[]" id="assign_kamar_select_{{ $guest->id }}" multiple class="select select-bordered h-auto min-h-36 w-full py-2">
                      @foreach($availableRooms as $room)
                        <option value="{{ $room->kode_kamar ?? $room->nomor_kamar }}">
                          {{ $room->kode_kamar ?? $room->nomor_kamar }} — {{ $room->jenis_kamar }} — {{ $room->gedung }}
                        </option>
                      @endforeach
                    </select>
                    <p class="text-[11px] text-base-content/50">{{ $availableRooms->count() }} kamar tersedia.</p>
                    <button type="submit" class="btn btn-success btn-sm rounded-full normal-case">Approve & Assign</button>
                  </form>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="py-12 text-center text-sm text-base-content/50">Semua booking sudah terkonfirmasi.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.approve-form').forEach((form) => {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      Swal.fire({
        title: 'Konfirmasi Pembayaran?',
        text: 'Booking akan disetujui dan kamar akan di-assign.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0d0b61',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Konfirmasi!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) form.submit();
      });
    });
  });

  document.querySelectorAll('.reject-form').forEach((form) => {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      Swal.fire({
        title: 'Tolak Booking?',
        text: 'Booking akan ditolak permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Tolak!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
      }).then((result) => {
        if (result.isConfirmed) form.submit();
      });
    });
  });
});

function previewFile(id) {
  const input = document.getElementById('bukti_file_' + id);
  const container = document.getElementById('preview_' + id);
  const image = document.getElementById('preview_img_' + id);
  const pdf = document.getElementById('preview_pdf_' + id);
  const filename = document.getElementById('preview_filename_' + id);

  image.style.display = 'none';
  pdf.classList.add('hidden');
  container.classList.add('hidden');

  const file = input.files[0];
  if (!file) return;

  container.classList.remove('hidden');

  if (file.type.startsWith('image/')) {
    const reader = new FileReader();
    reader.onload = (event) => {
      image.src = event.target.result;
      image.style.display = 'block';
    };
    reader.readAsDataURL(file);
  } else if (file.type === 'application/pdf') {
    pdf.classList.remove('hidden');
    filename.textContent = file.name;
  }
}
</script>
@endsection
