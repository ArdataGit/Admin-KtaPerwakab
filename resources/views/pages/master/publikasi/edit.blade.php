@extends('layouts.app')

@section('content')

    <h4>Edit Publikasi</h4>

    <form method="POST" action="{{ route('publikasi.update', $data->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Judul</label>
        <input type="text" name="title" class="form-control mb-2" value="{{ $data->title }}" required>

        <label>Pembuat</label>
        <input type="text" name="creator" class="form-control mb-2" value="{{ $data->creator }}" required>

        <label>Deskripsi</label>
        <textarea name="description" class="form-control mb-2" rows="5">{{ $data->description }}</textarea>

        <hr>

        <h5>Foto yang Sudah Ada</h5>
        @if($data->photos->count() > 0)
            <div class="row mb-3">
                @foreach($data->photos as $photo)
                    <div class="col-md-3 mb-2">
                        <div class="card">
                            <img src="{{ asset('storage/' . $photo->file_path) }}" class="card-img-top" alt="Photo">
                            <div class="card-body p-2">
                                <button type="button" class="btn btn-danger btn-sm w-100" 
                                        onclick="deletePhoto({{ $photo->id }})">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted">Belum ada foto</p>
        @endif

        <label>Tambah Foto Baru</label>
        <div id="photo-container">
            <div class="photo-input-group mb-2">
                <input type="file" name="photos[]" class="form-control" accept="image/jpeg,image/jpg,image/png">
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-secondary mb-2" onclick="addPhotoInput()">
            <i class="fas fa-plus"></i> Tambah Foto Lagi
        </button>
        <br>
        <small class="text-muted">Format didukung: JPG, JPEG, PNG (Max 2MB per file)</small>

        <hr>

        <h5>Video yang Sudah Ada</h5>
        @if($data->videos->count() > 0)
            <ul class="list-group mb-3">
                @foreach($data->videos as $video)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ $video->link }}" target="_blank">{{ $video->link }}</a>
                        <button type="button" class="btn btn-danger btn-sm" 
                                onclick="deleteVideo({{ $video->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Belum ada video</p>
        @endif

        <label>Tambah Link Video Baru</label>
        <div id="video-container">
            <input type="text" name="videos[]" class="form-control mb-1" placeholder="https://youtube.com/...">
        </div>
        <button type="button" class="btn btn-sm btn-secondary mb-2" onclick="addVideoInput()">
            <i class="fas fa-plus"></i> Tambah Video Lagi
        </button>

        <br><br>
        <button type="submit" class="btn btn-success mt-3">
            <i class="fas fa-save"></i> Update
        </button>
        <a href="{{ route('publikasi.index') }}" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
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

        function deletePhoto(photoId) {
            if (!confirm('Yakin ingin menghapus foto ini?')) return;
            
            fetch(`/master/publikasi/photo/${photoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal menghapus foto');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            });
        }

        function deleteVideo(videoId) {
            if (!confirm('Yakin ingin menghapus video ini?')) return;
            
            fetch(`/master/publikasi/video/${videoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal menghapus video');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            });
        }
    </script>

@endsection
