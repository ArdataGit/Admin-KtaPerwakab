@extends('layouts.app')

@section('content')

    <h4>Tambah Publikasi</h4>

    <form method="POST" action="{{ route('publikasi.store') }}" enctype="multipart/form-data">
        @csrf

        <label>Judul</label>
        <input type="text" name="title" class="form-control mb-2" required>

        <label>Pembuat</label>
        <input type="text" name="creator" class="form-control mb-2" required>

        <label>Deskripsi</label>
        <textarea name="description" class="form-control mb-2" rows="5"></textarea>

        <label>Foto (boleh banyak)</label>
        <div id="photo-container">
            <div class="photo-input-group mb-2">
                <input type="file" name="photos[]" class="form-control" accept="image/jpeg,image/jpg,image/png">
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-secondary mb-2" onclick="addPhotoInput()">
            <i class="fas fa-plus"></i> Tambah Foto Lagi
        </button>
        <br>
        <small class="text-muted">
            Format didukung: JPG, JPEG, PNG (Max 2MB per file)
        </small>
        <br><br>

        <label>Link Video (opsional, bisa lebih dari 1)</label>
        <div id="video-container">
            <input type="text" name="videos[]" class="form-control mb-1" placeholder="https://youtube.com/...">
        </div>
        <button type="button" class="btn btn-sm btn-secondary mb-2" onclick="addVideoInput()">
            <i class="fas fa-plus"></i> Tambah Video Lagi
        </button>

        <br><br>
        <button class="btn btn-success mt-3">Simpan</button>
    </form>

    <script>
        function addPhotoInput() {
            const container = document.getElementById('photo-container');
            const newInput = document.createElement('div');
            newInput.className = 'photo-input-group mb-2 d-flex';
            newInput.innerHTML = `
                <input type="file" name="photos[]" class="form-control" accept="image/jpeg,image/jpg,image/png">
                <button type="button" class="btn btn-danger btn-sm ms-2" onclick="this.parentElement.remove()">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(newInput);
        }

        function addVideoInput() {
            const container = document.getElementById('video-container');
            const newInput = document.createElement('div');
            newInput.className = 'mb-1 d-flex';
            newInput.innerHTML = `
                <input type="text" name="videos[]" class="form-control" placeholder="https://youtube.com/...">
                <button type="button" class="btn btn-danger btn-sm ms-2" onclick="this.parentElement.remove()">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(newInput);
        }
    </script>

@endsection