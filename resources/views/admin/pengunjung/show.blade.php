@extends('layout')

@section('content')
@php
  $isCorporate = strtolower($p->jenis_tamu) == 'corporate';
  $isPaid = in_array($p->payment_status, ['paid', 'lunas']);
  $today = now()->format('Y-m-d');
  $isCheckedOut = ($p->check_out && $p->check_out <= $today) || $p->checked_out == true;
  $displayName = $isCorporate ? ($p->nama_pic ?? 'PIC') : $p->nama;
@endphp

<div class="mb-8">
  <span class="section-kicker">Data Pengunjung</span>
  <div class="mt-2 flex flex-wrap items-start justify-between gap-4">
    <div>
      <h1 class="section-heading">{{ $displayName }}</h1>
      <p class="mt-2 text-sm text-base-content/60">
        Jenis: {{ ucfirst($p->jenis_tamu) }} · Check-in: {{ \Carbon\Carbon::parse($p->check_in)->format('d M Y') }}
      </p>
    </div>
    <div class="flex flex-wrap gap-2">
      <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $isPaid ? 'bg-success/10 text-success' : ($p->payment_status == 'rejected' ? 'bg-error/10 text-error' : 'bg-warning/15 text-warning') }}">
        {{ $p->payment_status_label ?? $p->payment_status }}
      </span>
      @if($isCheckedOut)
        <span class="inline-flex rounded-full bg-error/10 px-3 py-1 text-xs font-medium text-error">
          Telah checkout · {{ \Carbon\Carbon::parse($p->check_out)->format('d M Y H:i') }}
        </span>
      @endif
    </div>
  </div>
</div>

<div class="grid gap-5 lg:grid-cols-2">
  <section class="soft-card p-6">
    <h2 class="text-xs font-semibold uppercase tracking-wider text-secondary">Informasi Tamu</h2>
    <dl class="mt-4 divide-y divide-base-200 text-sm">
      @if($isCorporate)
        <div class="flex justify-between gap-4 py-2.5">
          <dt class="text-base-content/60">Nama PIC</dt>
          <dd class="text-right font-medium">{{ $p->nama_pic ?? '-' }}</dd>
        </div>
        <div class="flex justify-between gap-4 py-2.5">
          <dt class="text-base-content/60">No. Telp PIC</dt>
          <dd class="text-right font-medium">
            {{ $p->no_telp_pic ?? '-' }}
            @if($p->no_telp_pic)
              @php $wa = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $p->no_telp_pic); @endphp
              <a href="{{ $wa }}" target="_blank" rel="noopener" class="mt-1 block text-xs font-medium text-[#25D366] hover:underline">
                <i class="fab fa-whatsapp"></i> Chat WhatsApp
              </a>
            @endif
          </dd>
        </div>
      @else
        <div class="flex justify-between gap-4 py-2.5">
          <dt class="text-base-content/60">Nama Lengkap</dt>
          <dd class="text-right font-medium">{{ $p->nama }}</dd>
        </div>
        <div class="flex justify-between gap-4 py-2.5">
          <dt class="text-base-content/60">No. Identitas</dt>
          <dd class="text-right font-medium">{{ $p->no_identitas ?? '-' }} <span class="text-xs text-base-content/50">({{ strtoupper($p->identity_type ?? 'KTP') }})</span></dd>
        </div>
        <div class="flex justify-between gap-4 py-2.5">
          <dt class="text-base-content/60">No. Telepon</dt>
          <dd class="text-right font-medium">{{ $p->no_telp ?? '-' }}</dd>
        </div>
      @endif
      <div class="flex justify-between gap-4 py-2.5">
        <dt class="text-base-content/60">Jenis Tamu</dt>
        <dd class="text-right">
          <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $isCorporate ? 'bg-success/10 text-success' : 'bg-info/10 text-info' }}">
            {{ ucfirst($p->jenis_tamu) }}
          </span>
        </dd>
      </div>
    </dl>
  </section>

  <section class="soft-card p-6">
    <h2 class="text-xs font-semibold uppercase tracking-wider text-secondary">Informasi Booking</h2>
    <dl class="mt-4 divide-y divide-base-200 text-sm">
      <div class="flex justify-between gap-4 py-2.5">
        <dt class="text-base-content/60">Check-in</dt>
        <dd class="text-right font-medium">{{ \Carbon\Carbon::parse($p->check_in)->format('d M Y') }}</dd>
      </div>
      <div class="flex justify-between gap-4 py-2.5">
        <dt class="text-base-content/60">Check-out</dt>
        <dd class="text-right font-medium">{{ \Carbon\Carbon::parse($p->check_out)->format('d M Y') }}</dd>
      </div>
      <div class="flex justify-between gap-4 py-2.5">
        <dt class="text-base-content/60">Kode Kamar</dt>
        <dd class="text-right font-semibold text-primary">{{ $p->kode_kamar ?? '-' }}</dd>
      </div>
      @if($isCorporate)
        <div class="flex justify-between gap-4 py-2.5">
          <dt class="text-base-content/60">Jumlah Kamar</dt>
          <dd class="text-right font-medium">{{ $p->jumlah_kamar ?? '-' }} kamar</dd>
        </div>
        <div class="flex justify-between gap-4 py-2.5">
          <dt class="text-base-content/60">Jumlah Peserta</dt>
          <dd class="text-right font-medium">{{ $p->jumlah_peserta ?? '-' }} orang</dd>
        </div>
      @endif
    </dl>
  </section>

  @if($isCorporate)
    <section class="soft-card p-6">
      <h2 class="text-xs font-semibold uppercase tracking-wider text-secondary">Detail Corporate</h2>
      <dl class="mt-4 divide-y divide-base-200 text-sm">
        <div class="flex justify-between gap-4 py-2.5">
          <dt class="text-base-content/60">Nama Kegiatan</dt>
          <dd class="text-right font-medium">{{ $p->nama_kegiatan ?? '-' }}</dd>
        </div>
        <div class="flex justify-between gap-4 py-2.5">
          <dt class="text-base-content/60">Asal Persyarikatan</dt>
          <dd class="text-right font-medium">{{ $p->asal_persyarikatan ?? '-' }}</dd>
        </div>
        <div class="flex justify-between gap-4 py-2.5">
          <dt class="text-base-content/60">Tanggal Persyarikatan</dt>
          <dd class="text-right font-medium">
            {{ $p->tanggal_persyarikatan ? \Carbon\Carbon::parse($p->tanggal_persyarikatan)->format('d M Y') : '-' }}
          </dd>
        </div>
      </dl>
    </section>
  @endif

  @foreach([
    'Bukti Identitas' => $p->bukti_identitas,
    'Bukti Pembayaran' => $p->bukti_pembayaran,
  ] as $label => $file)
    <section class="soft-card p-6">
      <h2 class="text-xs font-semibold uppercase tracking-wider text-secondary">{{ $label }}</h2>
      @if($file)
        @php
          $fileUrl = \Illuminate\Support\Facades\Storage::url($file);
          $isPdf = strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'pdf';
        @endphp
        <a href="{{ $fileUrl }}" target="_blank" rel="noopener" class="btn btn-ghost btn-xs mt-3 rounded-full normal-case text-secondary">
          <i class="fas {{ $isPdf ? 'fa-file-pdf' : 'fa-image' }} text-xs"></i>
          {{ $isPdf ? 'Lihat PDF' : 'Lihat Gambar' }}
        </a>
        @if(!$isPdf)
          <div class="mt-3 rounded-xl border border-base-200 bg-base-200/40 p-2 text-center">
            <img src="{{ $fileUrl }}" alt="{{ $label }}" class="mx-auto max-h-48 rounded-lg object-contain" onerror="this.style.display='none'">
          </div>
        @endif
      @else
        <p class="mt-4 text-sm text-base-content/50">Belum diupload.</p>
      @endif
    </section>
  @endforeach
</div>

<div class="mt-8 flex flex-wrap gap-3 border-t border-base-200 pt-6">
  <a href="{{ route('pengunjung.index') }}" class="btn btn-ghost rounded-full normal-case">Kembali</a>

  @if($isCheckedOut)
    <button type="button" class="btn btn-disabled rounded-full normal-case" disabled>Sudah Check-Out</button>
  @elseif(!$p->bukti_identitas)
    <a href="{{ route('pengunjung.checkin', $p->id) }}" class="btn btn-secondary rounded-full normal-case">Check-In</a>
  @else
    <form action="{{ route('pengunjung.checkout', $p->id) }}" method="POST" class="checkout-form">
      @csrf
      <button type="submit" class="btn btn-primary rounded-full normal-case">Check-Out</button>
    </form>
  @endif

  <a href="{{ route('pengunjung.edit', $p->id) }}" class="btn btn-ghost rounded-full normal-case border border-base-300">Edit Data</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const checkoutForm = document.querySelector('.checkout-form');
  checkoutForm?.addEventListener('submit', function (event) {
    event.preventDefault();
    Swal.fire({
      title: 'Check-Out?',
      text: 'Kamar akan dikembalikan ke status kosong.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#0d0b61',
      cancelButtonColor: '#64748b',
      confirmButtonText: 'Ya, Check-Out!',
      cancelButtonText: 'Batal',
      reverseButtons: true,
    }).then((result) => {
      if (result.isConfirmed) checkoutForm.submit();
    });
  });
});
</script>
@endsection
