@extends('layouts.app')

@section('title', 'Edit Publikasi')

@section('page-title', 'Edit Publikasi')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Publikasi</h2>
        <a href="{{ route('publikasi.index') }}"
           class="px-5 py-2.5 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <form method="POST" action="{{ route('publikasi.update', $data->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Judul -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $data->title) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                           placeholder="Masukkan judul publikasi" required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pembuat -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pembuat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="creator" value="{{ old('creator', $data->creator) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                           placeholder="Nama pembuat" required>
                    @error('creator')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea name="description" rows="5"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                              placeholder="Deskripsi publikasi...">{{ old('description', $data->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Existing Photos -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-images text-[#3E9A3E] mr-2"></i>Foto yang Sudah Ada
                </h3>
                @if($data->photos->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($data->photos as $photo)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $photo->file_path) }}" 
                                     class="w-full h-32 object-cover rounded-lg shadow-md"
                                     alt="Photo">
                                <button type="button" onclick="deletePhoto({{ $photo->id }})"
                                        class="absolute top-2 right-2 px-2 py-1 bg-red-500 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Belum ada foto</p>
                @endif
            </div>

            <!-- Add New Photos -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tambah Foto Baru
                </label>
                <div id="photo-container" class="space-y-2">
                    <div class="photo-input-group">
                        <input type="file" name="photos[]" accept="image/jpeg,image/jpg,image/png"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20">
                    </div>
                </div>
                <button type="button" onclick="addPhotoInput()"
                        class="mt-2 px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Tambah Foto Lagi
                </button>
                <p class="text-gray-500 text-xs mt-2">Format: JPG, JPEG, PNG (Max: 2MB per file)</p>
            </div>

            <!-- Existing Videos -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-video text-[#3E9A3E] mr-2"></i>Video yang Sudah Ada
                </h3>
                @if($data->videos->count() > 0)
                    <div class="space-y-2">
                        @foreach($data->videos as $video)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <a href="{{ $video->link }}" target="_blank" 
                                   class="text-[#3E9A3E] hover:underline flex-1 truncate">
                                    <i class="fas fa-external-link-alt mr-2"></i>{{ $video->link }}
                                </a>
                                <button type="button" onclick="deleteVideo({{ $video->id }})"
                                        class="ml-3 px-3 py-1.5 bg-red-500 text-white text-xs rounded-lg hover:bg-red-600 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Belum ada video</p>
                @endif
            </div>

            <!-- Add New Videos -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tambah Link Video Baru
                </label>
                <div id="video-container" class="space-y-2">
                    <input type="text" name="videos[]"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                           placeholder="https://youtube.com/...">
                </div>
                <button type="button" onclick="addVideoInput()"
                        class="mt-2 px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Tambah Video Lagi
                </button>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-200">
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                    <i class="fas fa-save mr-2"></i>Update Publikasi
                </button>
                <a href="{{ route('publikasi.index') }}"
                   class="px-6 py-2.5 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition-all">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        function addPhotoInput() {
            const container = document.getElementById('photo-container');
            const newInput = document.createElement('div');
            newInput.className = 'photo-input-group flex gap-2';
            newInput.innerHTML = `
                <input type="file" name="photos[]" accept="image/jpeg,image/jpg,image/png"
                       class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20">
                <button type="button" onclick="this.parentElement.remove()"
                        class="px-4 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(newInput);
        }

        function addVideoInput() {
            const container = document.getElementById('video-container');
            const newInput = document.createElement('div');
            newInput.className = 'flex gap-2';
            newInput.innerHTML = `
                <input type="text" name="videos[]"
                       class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                       placeholder="https://youtube.com/...">
                <button type="button" onclick="this.parentElement.remove()"
                        class="px-4 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
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
