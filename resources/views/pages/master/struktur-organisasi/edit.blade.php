@extends('layouts.app')

@section('content')

    <h4>Edit Struktur Organisasi</h4>

    <form method="POST" action="{{ route('struktur-organisasi.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Judul <span class="text-danger">*</span></label>
            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" 
                   value="{{ old('judul', $data->judul) }}" required>
            @error('judul')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                      rows="5">{{ old('deskripsi', $data->deskripsi) }}</textarea>
            @error('deskripsi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        @if($data->file_path)
            <div class="mb-3">
                <label>File Saat Ini:</label><br>
                @php
                    $extension = pathinfo($data->file_path, PATHINFO_EXTENSION);
                    $fullUrl = asset('storage/' . $data->file_path);
                @endphp
                
                @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                    <img src="{{ $fullUrl }}" 
                         class="img-thumbnail" 
                         style="max-width: 300px;" 
                         alt="Current File">
                @elseif($extension === 'pdf')
                    <div>
                        <a href="{{ $fullUrl }}" 
                           target="_blank" 
                           class="btn btn-info btn-sm">
                            <i class="fas fa-file-pdf"></i> Lihat PDF
                        </a>
                        <a href="{{ $fullUrl }}" 
                           download 
                           class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                    </div>
                    
                    <!-- Preview PDF -->
                    <div class="mt-2">
                        <iframe src="{{ $fullUrl }}" 
                                width="100%" 
                                height="400px" 
                                style="border: 1px solid #ddd;">
                        </iframe>
                    </div>
                @else
                    <a href="{{ $fullUrl }}" 
                       target="_blank" 
                       class="btn btn-info btn-sm">
                        <i class="fas fa-file"></i> Lihat File ({{ strtoupper($extension) }})
                    </a>
                @endif
                
                <div class="mt-2">
                    <small class="text-muted">Path: {{ $data->file_path }}</small>
                </div>
            </div>
        @endif

        <div class="form-group">
            <label>Upload File Baru (opsional)</label>
            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" 
                   accept=".pdf,.jpg,.jpeg,.png">
            <small class="text-muted">Format: PDF, JPG, JPEG, PNG (Max 5MB). Kosongkan jika tidak ingin mengubah file.</small>
            @error('file')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Update
        </button>
        <a href="{{ route('struktur-organisasi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </form>

@endsection
