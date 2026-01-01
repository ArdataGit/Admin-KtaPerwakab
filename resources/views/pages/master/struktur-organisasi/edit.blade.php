@extends('layouts.app')

@section('title', 'Edit Struktur Organisasi')

@section('page-title', 'Edit Struktur Organisasi')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Struktur Organisasi</h2>
        <a href="{{ route('struktur-organisasi.index') }}"
           class="px-5 py-2.5 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <form method="POST" action="{{ route('struktur-organisasi.update', $data->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Judul -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" value="{{ old('judul', $data->judul) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20 @error('judul') border-red-500 @enderror"
                           placeholder="Masukkan judul struktur organisasi" required>
                    @error('judul')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" rows="5"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20 @error('deskripsi') border-red-500 @enderror"
                              placeholder="Deskripsi struktur organisasi (opsional)">{{ old('deskripsi', $data->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Saat Ini -->
                @if($data->file_path)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-file text-[#3E9A3E] mr-2"></i>File Saat Ini
                        </label>
                        @php
                            $extension = pathinfo($data->file_path, PATHINFO_EXTENSION);
                            $fullUrl = asset('storage/' . $data->file_path);
                        @endphp
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                                <!-- Image Preview -->
                                <img src="{{ $fullUrl }}" 
                                     class="w-full max-w-md rounded-lg shadow-md" 
                                     alt="Current File">
                            @elseif($extension === 'pdf')
                                <!-- PDF Actions -->
                                <div class="flex items-center gap-3 mb-3">
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
                                <iframe src="{{ $fullUrl }}" 
                                        class="w-full rounded-lg shadow-md" 
                                        style="height: 400px; border: 1px solid #e5e7eb;">
                                </iframe>
                            @else
                                <!-- Other File Types -->
                                <a href="{{ $fullUrl }}" 
                                   target="_blank" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors">
                                    <i class="fas fa-file mr-2"></i>Lihat File ({{ strtoupper($extension) }})
                                </a>
                            @endif
                            
                            <div class="mt-3">
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-folder mr-1"></i>Path: {{ $data->file_path }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Upload File Baru -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Upload File Baru (opsional)
                    </label>
                    <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20 @error('file') border-red-500 @enderror">
                    <p class="text-gray-500 text-xs mt-2">Format: PDF, JPG, JPEG, PNG (Max: 5MB). Kosongkan jika tidak ingin mengubah file.</p>
                    @error('file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-200">
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
                <a href="{{ route('struktur-organisasi.index') }}"
                   class="px-6 py-2.5 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition-all">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>

@endsection
