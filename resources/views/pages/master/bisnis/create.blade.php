@extends('layouts.app')

@section('content')

<h4>Tambah Bisnis</h4>

<form method="POST" action="{{ route('bisnis.store') }}" enctype="multipart/form-data">
    @csrf

    <label>Nama Bisnis</label>
    <input type="text" name="nama" class="form-control mb-2" required>

    <label>Kategori</label>
    <input type="text" name="kategori" class="form-control mb-2">
  
    <label>Alamat</label>
    <textarea name="alamat" class="form-control mb-2" rows="2"
              placeholder="Alamat lengkap bisnis"></textarea>

    <label>Telepon</label>
    <input type="text" name="telepon" class="form-control mb-2"
           placeholder="08xxxxxxxxxx">

    <label>Email</label>
    <input type="email" name="email" class="form-control mb-2"
           placeholder="email@domain.com">

    <label>Website</label>
    <input type="url" name="website" class="form-control mb-3"
           placeholder="https://website.com">


    <label>Deskripsi</label>
    <textarea name="deskripsi" id="editor-deskripsi" class="form-control mb-3" ></textarea>


    {{-- FOTO --}}
    <label>Foto Bisnis (boleh banyak)</label>
    <div id="photo-container">
        <div class="photo-input-group mb-2">
            <input type="file" name="images[]" class="form-control"
                   accept="image/jpeg,image/jpg,image/png,image/webp">
        </div>
    </div>

    <button type="button" class="btn btn-sm btn-secondary mb-2" onclick="addPhotoInput()">
        <i class="fas fa-plus"></i> Tambah Foto
    </button>

    <br>
    <small class="text-muted">
        Format: JPG, JPEG, PNG, WEBP (Max 2MB per file)
    </small>

    <br><br>

    {{-- VIDEO --}}
    <label>Link Video (YouTube / Embed, opsional)</label>
    <div id="video-container">
        <input type="text" name="youtube_urls[]" class="form-control mb-1"
               placeholder="https://youtube.com/watch?v=...">
    </div>

    <button type="button" class="btn btn-sm btn-secondary mb-2" onclick="addVideoInput()">
        <i class="fas fa-plus"></i> Tambah Video
    </button>

    <br><br>

    <button class="btn btn-success mt-3">
        <i class="fas fa-save"></i> Simpan
    </button>
</form>
{{-- CKEditor --}}
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

<script>
    ClassicEditor
        .create(document.querySelector('#editor-deskripsi'), {
            toolbar: [
                'heading',
                '|',
                'bold', 'italic', 'underline', 'link',
                '|',
                'bulletedList', 'numberedList',
                '|',
                'blockQuote',
                '|',
                'undo', 'redo'
            ]
        })
        .catch(error => {
            console.error(error);
        });
</script>

<script>
    function addPhotoInput() {
        const container = document.getElementById('photo-container');
        const div = document.createElement('div');
        div.className = 'photo-input-group mb-2 d-flex';
        div.innerHTML = `
            <input type="file" name="images[]" class="form-control"
                   accept="image/jpeg,image/jpg,image/png,image/webp">
            <button type="button" class="btn btn-danger btn-sm ms-2"
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
            <button type="button" class="btn btn-danger btn-sm ms-2"
                    onclick="this.parentElement.remove()">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(div);
    }
</script>

@endsection
