@extends('layout')

@section('content')
<div class="mb-8">
  <span class="section-kicker">Konten Situs</span>
  <div class="mt-2 flex flex-wrap items-end justify-between gap-4">
    <div>
      <h1 class="section-heading">Edit Beranda</h1>
      <p class="mt-2 text-sm text-base-content/60">Perbarui informasi kontak dan teks slider yang tampil di halaman utama.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost btn-sm rounded-full normal-case border border-base-300">
      <i class="fas fa-arrow-left text-xs"></i> Kembali
    </a>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success mb-6 text-sm">{{ session('success') }}</div>
@endif

<form id="form-beranda" action="{{ route('beranda.update', 1) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
  @csrf
  @method('PUT')

  <section class="soft-card p-6">
    <h2 class="text-sm font-semibold text-primary">Informasi Kontak</h2>
    <p class="mt-1 text-xs text-base-content/60">Ubah field lalu klik Simpan pada baris yang bersangkutan.</p>

    <div class="mt-5 grid gap-4 lg:grid-cols-2">
      @foreach([
        'instagram' => 'Instagram',
        'email' => 'Email',
        'whatsapp' => 'WhatsApp',
        'location' => 'Lokasi',
        'maps_link' => 'Tautan Maps',
      ] as $field => $label)
        <div>
          <label for="{{ $field }}" class="mb-1.5 block text-xs font-medium text-base-content/70">{{ $label }}</label>
          <div class="flex gap-2">
            <input id="{{ $field }}" name="{{ $field }}" type="{{ $field == 'email' ? 'email' : 'text' }}" value="{{ $data->$field }}" class="input input-bordered w-full" style="opacity: 0.5;">
            <button type="button" class="btn btn-primary btn-sm shrink-0 rounded-full normal-case" data-field="{{ $field }}">Simpan</button>
          </div>
        </div>
      @endforeach
    </div>
  </section>

  @foreach([1, 2] as $index)
    @php
      $imageField = "slider{$index}_image";
      $textField = "slider{$index}_text";
      $image = $data->$imageField;
    @endphp

    <section class="soft-card p-6">
      <span class="section-kicker">Slider {{ $index }}</span>

      <div class="mt-4 grid gap-5 lg:grid-cols-3">
        <div>
          <span class="mb-1.5 block text-xs font-medium text-base-content/70">Gambar Saat Ini</span>
          @if($image)
            <img src="{{ asset($image) }}" alt="Slider {{ $index }}" class="h-32 w-full rounded-xl border border-base-200 object-cover">
          @else
            <div class="flex h-32 items-center justify-center rounded-xl border border-dashed border-base-300 text-xs text-base-content/40">Belum ada gambar</div>
          @endif
        </div>

        <div>
          <label for="{{ $imageField }}" class="mb-1.5 block text-xs font-medium text-base-content/70">Gambar Baru</label>
          <input id="{{ $imageField }}" type="file" name="{{ $imageField }}" class="file-input file-input-bordered w-full">
        </div>

        <div>
          <label for="{{ $textField }}" class="mb-1.5 block text-xs font-medium text-base-content/70">Teks Slider</label>
          <textarea id="{{ $textField }}" name="{{ $textField }}" rows="3" class="textarea textarea-bordered w-full" style="opacity: 0.5;">{{ $data->$textField }}</textarea>
        </div>
      </div>

      <div class="mt-4 flex justify-end">
        <button type="button" class="btn btn-primary btn-sm rounded-full normal-case" data-field="slider{{ $index }}">Simpan Slider {{ $index }}</button>
      </div>
    </section>
  @endforeach
</form>

<script>
  const form = document.getElementById('form-beranda');
  const fields = [
    'instagram', 'email', 'whatsapp', 'location', 'maps_link',
    'slider1_text', 'slider2_text',
  ];

  const oldValues = {};
  fields.forEach((id) => {
    const element = document.getElementById(id);
    if (element) oldValues[id] = element.value;
  });

  fields.forEach((id) => {
    const element = document.getElementById(id);
    if (!element) return;
    element.addEventListener('input', () => {
      element.style.opacity = element.value !== oldValues[id] ? '1' : '0.5';
    });
  });

  document.querySelectorAll('.btn-save, [data-field]').forEach((button) => {
    button.addEventListener('click', () => {
      const field = button.dataset.field;
      if (!field) return;

      let message;
      if (field.startsWith('slider')) {
        message = `Simpan perubahan pada ${field.replace('_', ' ')}?`;
      } else {
        const element = document.getElementById(field);
        const newValue = element?.value.trim();
        if (newValue === oldValues[field]) {
          alert(`Tidak ada perubahan pada ${field}.`);
          return;
        }
        message = `Ubah ${field.replace('_', ' ')} menjadi "${newValue}"?`;
      }

      if (confirm(message)) form.submit();
    });
  });
</script>
@endsection
