@extends('layouts.app')

@section('content')

<h4>Edit Bisnis</h4>

{{-- ================= FORM UPDATE ================= --}}
<form method="POST"
      action="{{ route('bisnis.update', $bisnis->id) }}"
      enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label>Nama Bisnis</label>
    <input type="text" name="nama"
           class="form-control mb-2"
           value="{{ old('nama', $bisnis->nama) }}"
           required>

      <label>Kategori Bisnis</label>
      <select name="kategori" class="form-control mb-2" required>
          <option value="">-- Pilih Kategori Bisnis --</option>

          @php
              $kategoriBisnis = [
                  'Kuliner',
                  'Fashion',
                  'Kerajinan',
                  'Jasa',
                  'Pertanian',
                  'Perikanan',
                  'Peternakan',
                  'Perdagangan',
                  'Industri Rumah Tangga',
                  'Lainnya',
              ];
              $selectedKategori = old('kategori', $bisnis->kategori);
          @endphp

          @foreach ($kategoriBisnis as $kat)
              <option value="{{ $kat }}" {{ $selectedKategori === $kat ? 'selected' : '' }}>
                  {{ $kat }}
              </option>
          @endforeach
      </select>

  
  
    <label>Alamat</label>
    <textarea name="alamat" class="form-control mb-2" rows="2">
    {{ old('alamat', $bisnis->alamat) }}
    </textarea>

    <label>Telepon</label>
    <input type="text" name="telepon"
           class="form-control mb-2"
           value="{{ old('telepon', $bisnis->telepon) }}">

    <label>Email</label>
    <input type="email" name="email"
           class="form-control mb-2"
           value="{{ old('email', $bisnis->email) }}">

    <label>Website</label>
    <input type="url" name="website"
           class="form-control mb-3"
           value="{{ old('website', $bisnis->website) }}">


    <label>Deskripsi</label>
    <textarea name="deskripsi"
              id="editor-deskripsi"
              class="form-control mb-3">{!! old('deskripsi', $bisnis->deskripsi) !!}</textarea>

    {{-- ================= TAMBAH FOTO BARU ================= --}}
    <label>Tambah Foto Baru</label>
    <div id="photo-container">
        <div class="photo-input-group mb-2">
            <input type="file" name="images[]"
                   class="form-control"
                   accept="image/jpeg,image/jpg,image/png,image/webp">
        </div>
    </div>

    <button type="button"
            class="btn btn-sm btn-secondary mb-3"
            onclick="addPhotoInput()">
        <i class="fas fa-plus"></i> Tambah Foto
    </button>

    {{-- ================= TAMBAH VIDEO BARU ================= --}}
    <label>Tambah Link Video</label>
    <div id="video-container">
        <input type="text"
               name="youtube_urls[]"
               class="form-control mb-1"
               placeholder="https://youtube.com/watch?v=...">
    </div>

    <button type="button"
            class="btn btn-sm btn-secondary mb-4"
            onclick="addVideoInput()">
        <i class="fas fa-plus"></i> Tambah Video
    </button>
<br/>
    <button class="btn btn-success">
        <i class="fas fa-save"></i> Update Bisnis
    </button>
</form>

<hr>

{{-- ================= FOTO EXISTING ================= --}}
<label>Foto Saat Ini</label>
<div class="row mb-4">
    @forelse($bisnis->images as $img)
        <div class="col-md-3 text-center mb-3">
            <img src="{{ asset('storage/'.$img->file_path) }}"
                 class="img-fluid rounded mb-2"
                 style="max-height:150px;object-fit:cover">

            <form method="POST"
                  action="{{ route('bisnis.media.destroy', $img->id) }}"
                  onsubmit="return confirm('Hapus foto ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger w-100">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
        </div>
    @empty
        <p class="text-muted">Belum ada foto.</p>
    @endforelse
</div>

{{-- ================= VIDEO EXISTING ================= --}}
<label>Video Saat Ini</label>
<ul class="list-group mb-4">
    @forelse($bisnis->videos as $vid)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <a href="{{ $vid->url }}" target="_blank">{{ $vid->url }}</a>

            <form method="POST"
                  action="{{ route('bisnis.media.destroy', $vid->id) }}"
                  onsubmit="return confirm('Hapus video ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </li>
    @empty
        <li class="list-group-item text-muted">Belum ada video.</li>
    @endforelse
</ul>

{{-- ================= CKEDITOR ================= --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor-deskripsi'))
        .catch(error => console.error(error));
</script>

{{-- ================= HEIGHT EDITOR ================= --}}
<style>
    .ck-editor__editable {
        min-height: 350px;
    }
</style>

{{-- ================= JS FOTO & VIDEO ================= --}}
<script>
    function addPhotoInput() {
        const container = document.getElementById('photo-container');
        const div = document.createElement('div');
        div.className = 'photo-input-group mb-2 d-flex';
        div.innerHTML = `
            <input type="file" name="images[]" class="form-control"
                   accept="image/jpeg,image/jpg,image/png,image/webp">
            <button type="button"
                    class="btn btn-danger btn-sm ms-2"
                    onclick="this.parentElement.remove()">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(div);
    }

    function addVideoInput() {
        const container = document.getElementById('video-container');
        const div = document.createElement('div');
        div.className = 'mb-1 d-flex';
        div.innerHTML = `
            <input type="text" name="youtube_urls[]" class="form-control"
                   placeholder="https://youtube.com/watch?v=...">
            <button type="button"
                    class="btn btn-danger btn-sm ms-2"
                    onclick="this.parentElement.remove()">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(div);
    }
</script>

@endsection
