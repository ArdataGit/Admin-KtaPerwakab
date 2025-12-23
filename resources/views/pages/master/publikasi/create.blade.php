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
        <input type="file" name="photos[]" multiple class="form-control mb-2">

        <label>Link Video (opsional, bisa lebih dari 1)</label>
        <input type="text" name="videos[]" class="form-control mb-1" placeholder="https://youtube.com/...">
        <input type="text" name="videos[]" class="form-control mb-1">

        <button class="btn btn-success mt-3">Simpan</button>
    </form>

@endsection