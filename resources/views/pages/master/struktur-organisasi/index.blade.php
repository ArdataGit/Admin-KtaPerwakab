@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Struktur Organisasi</h4>
        
        @if(!$data)
            <a href="{{ route('struktur-organisasi.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Struktur Organisasi
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if($data)
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $data->judul }}</h5>
                <div>
                    <a href="{{ route('struktur-organisasi.edit') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('struktur-organisasi.destroy') }}" 
                          style="display:inline-block" 
                          onsubmit="return confirm('Yakin ingin menghapus struktur organisasi?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Deskripsi:</strong>
                    <p>{{ $data->deskripsi ?? '-' }}</p>
                </div>

                @if($data->file_path)
                    <div class="mb-3">
                        <strong>File:</strong><br>
                        @php
                            $extension = pathinfo($data->file_path, PATHINFO_EXTENSION);
                            $fullUrl = asset('storage/' . $data->file_path);
                        @endphp
                        
                        @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                            <img src="{{ $fullUrl }}" 
                                 class="img-fluid mt-2" 
                                 style="max-width: 600px;" 
                                 alt="Struktur Organisasi">
                        @elseif($extension === 'pdf')
                            <div class="mt-2">
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
                            
                            <!-- Preview PDF dengan iframe -->
                            <div class="mt-3">
                                <iframe src="{{ $fullUrl }}" 
                                        width="100%" 
                                        height="600px" 
                                        style="border: 1px solid #ddd;">
                                </iframe>
                            </div>
                        @else
                            <a href="{{ $fullUrl }}" 
                               target="_blank" 
                               class="btn btn-info btn-sm mt-2">
                                <i class="fas fa-download"></i> Download File ({{ strtoupper($extension) }})
                            </a>
                        @endif
                        
                        <div class="mt-2">
                            <small class="text-muted">Path: {{ $data->file_path }}</small>
                        </div>
                    </div>
                @endif

                <small class="text-muted">
                    Terakhir diperbarui: {{ $data->updated_at->format('d M Y H:i') }}
                </small>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Belum ada struktur organisasi. 
            <a href="{{ route('struktur-organisasi.create') }}">Klik di sini</a> untuk membuat.
        </div>
    @endif

@endsection
