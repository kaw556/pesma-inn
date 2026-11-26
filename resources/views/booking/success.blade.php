@extends('layouts.app')

@section('title', 'Booking Berhasil · Pesma Inn')

@section('content')
@php
  $isCash = ($pengunjung->metode_pembayaran ?? '') === 'Via Cash';
  $isConfirmed = in_array($pengunjung->payment_status, ['lunas', 'paid']);

  $statusText = $isCash && !$isConfirmed
    ? 'Menunggu Konfirmasi Admin'
    : ($isCash ? 'Lunas · Bayar di Tempat' : ($isConfirmed ? 'Lunas' : 'Menunggu Konfirmasi'));

  $buktiDetails = [
    ['Nama', $pengunjung->jenis_tamu == 'corporate' ? ($pengunjung->nama_pic ?? $pengunjung->nama) : $pengunjung->nama],
    ['Jenis Booking', ucfirst($pengunjung->jenis_tamu)],
    ['Metode Pembayaran', $pengunjung->metode_pembayaran ?? '-'],
    ['Check-in', \Carbon\Carbon::parse($pengunjung->check_in)->format('d M Y')],
    ['Check-out', \Carbon\Carbon::parse($pengunjung->check_out)->format('d M Y')],
    ['Total Pembayaran', 'Rp ' . number_format($totalPembayaran, 0, ',', '.')],
    ['Tanggal Booking', \Carbon\Carbon::parse($pengunjung->created_at)->format('d M Y, H:i')],
  ];
@endphp

<div class="mx-auto max-w-2xl">
  <div id="buktiCard" class="soft-card p-8 text-center sm:p-10">
    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-success/10 text-success">
      <i class="fas fa-check text-2xl"></i>
    </div>

    <h1 class="mt-5 text-2xl font-semibold text-primary">Booking Berhasil!</h1>
    <p class="mt-2 text-sm text-base-content/60">
      @if($isCash && !$isConfirmed)
        Terima kasih telah memesan. Pembayaran cash Anda menunggu verifikasi admin — admin akan memastikan jumlah uang dan mencetak invoice.
      @elseif($isCash)
        Pembayaran cash telah dikonfirmasi admin. Terima kasih, invoice akan dicetak oleh petugas saat Anda check-in.
      @else
        Terima kasih telah memesan. Pesanan Anda sedang diproses dan menunggu konfirmasi admin.
      @endif
    </p>

    <div class="mt-4">
      @if($isCash && !$isConfirmed)
        <span class="inline-flex rounded-full bg-warning/15 px-4 py-1.5 text-xs font-semibold text-warning">Menunggu Konfirmasi Admin</span>
      @elseif($isCash)
        <span class="inline-flex rounded-full bg-secondary/10 px-4 py-1.5 text-xs font-semibold text-secondary">Lunas · Bayar di Tempat</span>
      @else
        <span class="inline-flex rounded-full bg-warning/15 px-4 py-1.5 text-xs font-semibold text-warning">Menunggu Konfirmasi</span>
      @endif
    </div>

    <dl class="mt-7 divide-y divide-base-200 rounded-2xl border border-base-200 bg-base-100 px-5 text-left text-sm">
      <div class="flex items-center justify-between gap-4 py-3">
        <dt class="text-base-content/60">Nama</dt>
        <dd class="text-right font-medium">
          {{ $pengunjung->jenis_tamu == 'corporate' ? ($pengunjung->nama_pic ?? $pengunjung->nama) : $pengunjung->nama }}
        </dd>
      </div>
      <div class="flex items-center justify-between gap-4 py-3">
        <dt class="text-base-content/60">Jenis Booking</dt>
        <dd class="font-medium">{{ ucfirst($pengunjung->jenis_tamu) }}</dd>
      </div>
      <div class="flex items-center justify-between gap-4 py-3">
        <dt class="text-base-content/60">Metode Pembayaran</dt>
        <dd class="text-right font-medium">{{ $pengunjung->metode_pembayaran ?? '-' }}</dd>
      </div>
      <div class="flex items-center justify-between gap-4 py-3">
        <dt class="text-base-content/60">Check-in</dt>
        <dd class="font-medium">{{ \Carbon\Carbon::parse($pengunjung->check_in)->format('d M Y') }}</dd>
      </div>
      <div class="flex items-center justify-between gap-4 py-3">
        <dt class="text-base-content/60">Check-out</dt>
        <dd class="font-medium">{{ \Carbon\Carbon::parse($pengunjung->check_out)->format('d M Y') }}</dd>
      </div>
      <div class="flex items-center justify-between gap-4 py-3">
        <dt class="text-base-content/60">Total Pembayaran</dt>
        <dd class="font-semibold text-primary">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</dd>
      </div>
      <div class="flex items-center justify-between gap-4 py-3">
        <dt class="text-base-content/60">Tanggal Booking</dt>
        <dd class="font-medium">{{ \Carbon\Carbon::parse($pengunjung->created_at)->format('d M Y, H:i') }}</dd>
      </div>
    </dl>

    <div class="mt-7 flex flex-wrap justify-center gap-3">
      @if(!$isConfirmed)
        <button type="button" id="downloadBukti" class="btn btn-primary rounded-full normal-case">
          <i class="fas fa-download text-xs"></i> Download Bukti Pesan
        </button>
      @endif
      <a href="{{ url('/') }}" class="btn btn-ghost rounded-full normal-case border border-base-300">
        <i class="fas fa-house text-xs"></i> Kembali ke Beranda
      </a>
    </div>
  </div>
</div>

<script>
document.getElementById('downloadBukti')?.addEventListener('click', () => {
  const details = @json($buktiDetails);
  const status = @json($statusText);
  const confirmed = @json($isConfirmed);
  const bookingId = @json($pengunjung->id);

  const roundRect = (ctx, x, y, width, height, radius) => {
    ctx.beginPath();
    ctx.moveTo(x + radius, y);
    ctx.arcTo(x + width, y, x + width, y + height, radius);
    ctx.arcTo(x + width, y + height, x, y + height, radius);
    ctx.arcTo(x, y + height, x, y, radius);
    ctx.arcTo(x, y, x + width, y, radius);
    ctx.closePath();
  };

  const canvas = document.createElement('canvas');
  canvas.width = 900;
  canvas.height = 720;
  const ctx = canvas.getContext('2d');
  const font = '"Instrument Sans", Arial, sans-serif';

  ctx.fillStyle = '#f4f7fb';
  ctx.fillRect(0, 0, canvas.width, canvas.height);

  ctx.save();
  roundRect(ctx, 30, 30, 840, 660, 24);
  ctx.clip();
  ctx.fillStyle = '#ffffff';
  ctx.fillRect(30, 30, 840, 660);
  ctx.fillStyle = '#0d0b61';
  ctx.fillRect(30, 30, 840, 120);
  ctx.fillStyle = '#ffffff';
  ctx.font = `700 28px ${font}`;
  ctx.fillText('PESMA INN', 70, 82);
  ctx.font = `400 15px ${font}`;
  ctx.fillText('Bukti Pemesanan · KH. Mas Mansur, Surakarta', 70, 112);
  ctx.restore();

  ctx.strokeStyle = '#e2e8f0';
  ctx.lineWidth = 1;
  roundRect(ctx, 30, 30, 840, 660, 24);
  ctx.stroke();

  ctx.beginPath();
  ctx.arc(75, 188, 26, 0, Math.PI * 2);
  ctx.fillStyle = '#d1fae5';
  ctx.fill();
  ctx.strokeStyle = '#16a34a';
  ctx.lineWidth = 4;
  ctx.beginPath();
  ctx.moveTo(63, 188);
  ctx.lineTo(72, 197);
  ctx.lineTo(89, 178);
  ctx.stroke();

  ctx.fillStyle = '#0d0b61';
  ctx.font = `700 32px ${font}`;
  ctx.fillText('Booking Berhasil!', 120, 199);

  ctx.font = `600 15px ${font}`;
  const badgeWidth = ctx.measureText(status).width + 36;
  roundRect(ctx, 70, 235, badgeWidth, 34, 17);
  ctx.fillStyle = confirmed ? '#e0f7f8' : '#fef3c7';
  ctx.fill();
  ctx.fillStyle = confirmed ? '#0f6b75' : '#92400e';
  ctx.fillText(status, 88, 258);

  let y = 320;
  details.forEach(([label, value]) => {
    ctx.textAlign = 'left';
    ctx.fillStyle = '#64748b';
    ctx.font = `500 14px ${font}`;
    ctx.fillText(label, 70, y);
    ctx.textAlign = 'right';
    ctx.fillStyle = '#0f172a';
    ctx.font = `600 15px ${font}`;
    ctx.fillText(String(value), 830, y);
    ctx.textAlign = 'left';
    ctx.strokeStyle = '#e2e8f0';
    ctx.beginPath();
    ctx.moveTo(70, y + 14);
    ctx.lineTo(830, y + 14);
    ctx.stroke();
    y += 44;
  });

  ctx.fillStyle = '#64748b';
  ctx.font = `400 13px ${font}`;
  ctx.fillText('Invoice dicetak oleh admin setelah pembayaran dikonfirmasi.', 70, 665);

  canvas.toBlob((blob) => {
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `bukti-booking-${bookingId}.png`;
    document.body.appendChild(link);
    link.click();
    link.remove();
    setTimeout(() => URL.revokeObjectURL(url), 1000);
  });
});
</script>
@endsection
