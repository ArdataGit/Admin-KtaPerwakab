@extends('layouts.app')

@section('content')

    <h4>Buat Struktur Organisasi</h4>

    <form method="POST" action="{{ route('struktur-organisasi.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Judul <span class="text-danger">*</span></label>
            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" 
                   value="{{ old('judul') }}" required>
            @error('judul')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                      rows="5">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Upload File</label>
            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" 
                   accept=".pdf,.jpg,.jpeg,.png">
            <small class="text-muted">Format: PDF, JPG, JPEG, PNG (Max 5MB)</small>
            @error('file')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Simpan
        </button>
        <a href="{{ route('struktur-organisasi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </form>

@endsection
