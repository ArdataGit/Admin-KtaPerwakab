@extends('layouts.app')

@section('title', 'Struktur Organisasi')

@section('page-title', 'Struktur Organisasi')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Struktur Organisasi</h2>
        @if(!$data)
            <a href="{{ route('struktur-organisasi.create') }}"
               class="px-5 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                <i class="fas fa-plus mr-2"></i>Buat Struktur Organisasi
            </a>
        @endif
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($data)
        <!-- Content Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gray-50">
                <h3 class="text-xl font-bold text-gray-900">{{ $data->judul }}</h3>
                <div class="flex items-center gap-2">
                    <a href="{{ route('struktur-organisasi.edit', $data->id) }}"
                       class="px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-lg hover:bg-yellow-600 transition-colors">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <form method="POST" action="{{ route('struktur-organisasi.destroy', $data->id) }}" 
                          class="inline-block"
                          onsubmit="return confirm('Yakin ingin menghapus struktur organisasi?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition-colors">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6">
                <!-- Deskripsi -->
                @if($data->deskripsi)
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-align-left text-[#3E9A3E] mr-2"></i>Deskripsi
                        </h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700 whitespace-pre-line">{{ $data->deskripsi }}</p>
                        </div>
                    </div>
                @endif

                <!-- File -->
                @if($data->file_path)
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-file text-[#3E9A3E] mr-2"></i>File Struktur Organisasi
                        </h4>
                        @php
                            $extension = pathinfo($data->file_path, PATHINFO_EXTENSION);
                            $fullUrl = asset('storage/' . $data->file_path);
                        @endphp
                        
                        @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                            <!-- Image Preview -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <img src="{{ $fullUrl }}" 
                                     class="w-full max-w-4xl mx-auto rounded-lg shadow-md" 
                                     alt="Struktur Organisasi">
                            </div>
                        @elseif($extension === 'pdf')
                            <!-- PDF Actions -->
                            <div class="flex items-center gap-3 mb-4">
                                <a href="{{ $fullUrl }}" 
                                   target="_blank" 
                                   class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors">
                                    <i class="fas fa-file-pdf mr-2"></i>Lihat PDF
                                </a>
                                <a href="{{ $fullUrl }}" 
                                   download 
                                   class="px-4 py-2 bg-[#3E9A3E] text-white text-sm font-medium rounded-lg hover:bg-[#2d7a2d] transition-colors">
                                    <i class="fas fa-download mr-2"></i>Download PDF
                                </a>
                            </div>
                            
                            <!-- PDF Preview -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <iframe src="{{ $fullUrl }}" 
                                        class="w-full rounded-lg shadow-md" 
                                        style="height: 600px; border: 1px solid #e5e7eb;">
                                </iframe>
                            </div>
                        @else
                            <!-- Other File Types -->
                            <a href="{{ $fullUrl }}" 
                               target="_blank" 
                               class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors">
                                <i class="fas fa-download mr-2"></i>Download File ({{ strtoupper($extension) }})
                            </a>
                        @endif
                        
                        <div class="mt-3">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-folder mr-1"></i>Path: {{ $data->file_path }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Footer Info -->
                <div class="pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-clock mr-2"></i>Terakhir diperbarui: {{ $data->updated_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 text-xl mr-3 mt-1"></i>
                <div>
                    <p class="text-blue-700 font-medium">Belum ada struktur organisasi.</p>
                    <p class="text-blue-600 text-sm mt-1">
                        <a href="{{ route('struktur-organisasi.create') }}" 
                           class="underline hover:text-blue-800">Klik di sini</a> untuk membuat struktur organisasi baru.
                    </p>
                </div>
            </div>
        </div>
    @endif

@endsection
