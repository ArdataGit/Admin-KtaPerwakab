@extends('layouts.app')

@section('title', 'Edit Bisnis')

@section('page-title', 'Edit Bisnis')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Bisnis</h2>
        <a href="{{ route('bisnis.index') }}"
           class="px-5 py-2.5 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form method="POST" action="{{ route('bisnis.update', $bisnis->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Bisnis -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Bisnis <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama', $bisnis->nama) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                           placeholder="Masukkan nama bisnis" required>
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori
                    </label>
                    <input type="text" name="kategori" value="{{ old('kategori', $bisnis->kategori) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                           placeholder="Contoh: Kuliner, Fashion, Teknologi">
                    @error('kategori')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat
                    </label>
                    <textarea name="alamat" rows="2"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                              placeholder="Alamat lengkap bisnis">{{ old('alamat', $bisnis->alamat) }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telepon -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Telepon
                    </label>
                    <input type="text" name="telepon" value="{{ old('telepon', $bisnis->telepon) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                           placeholder="08xxxxxxxxxx">
                    @error('telepon')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email', $bisnis->email) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                           placeholder="email@domain.com">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Website -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Website
                    </label>
                    <input type="url" name="website" value="{{ old('website', $bisnis->website) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                           placeholder="https://website.com">
                    @error('website')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea id="editor-deskripsi" name="deskripsi" class="w-full">{!! old('deskripsi', $bisnis->deskripsi) !!}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tambah Foto Baru -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tambah Foto Baru
                    </label>
                    <div id="photo-container" class="space-y-2">
                        <div class="photo-input-group">
                            <input type="file" name="images[]" accept="image/jpeg,image/jpg,image/png,image/webp"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20">
                        </div>
                    </div>
                    <button type="button" onclick="addPhotoInput()"
                            class="mt-2 px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Tambah Foto
                    </button>
                    <p class="text-gray-500 text-xs mt-2">Format: JPG, JPEG, PNG, WEBP (Max: 2MB per file)</p>
                </div>

                <!-- Tambah Video Baru -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tambah Link Video
                    </label>
                    <div id="video-container" class="space-y-2">
                        <input type="text" name="youtube_urls[]"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                               placeholder="https://youtube.com/watch?v=...">
                    </div>
                    <button type="button" onclick="addVideoInput()"
                            class="mt-2 px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Tambah Video
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200">
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                    <i class="fas fa-save mr-2"></i>Update Bisnis
                </button>
                <a href="{{ route('bisnis.index') }}"
                   class="px-6 py-2.5 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition-all">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Existing Photos -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-images text-[#3E9A3E] mr-2"></i>Foto Saat Ini
        </h3>
        @if($bisnis->images->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($bisnis->images as $img)
                    <div class="relative group">
                        <img src="{{ asset('storage/'.$img->file_path) }}" 
                             class="w-full h-32 object-cover rounded-lg shadow-md"
                             alt="Photo">
                        <form method="POST" action="{{ route('bisnis.media.destroy', $img->id) }}"
                              onsubmit="return confirm('Hapus foto ini?')" class="mt-2">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="w-full px-2 py-1 bg-red-500 text-white text-xs rounded-lg hover:bg-red-600 transition-colors">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm">Belum ada foto.</p>
        @endif
    </div>

    <!-- Existing Videos -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-video text-[#3E9A3E] mr-2"></i>Video Saat Ini
        </h3>
        @if($bisnis->videos->count() > 0)
            <div class="space-y-2">
                @foreach($bisnis->videos as $vid)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <a href="{{ $vid->url }}" target="_blank" 
                           class="text-[#3E9A3E] hover:underline flex-1 truncate">
                            <i class="fas fa-external-link-alt mr-2"></i>{{ $vid->url }}
                        </a>
                        <form method="POST" action="{{ route('bisnis.media.destroy', $vid->id) }}"
                              onsubmit="return confirm('Hapus video ini?')" class="ml-3">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1.5 bg-red-500 text-white text-xs rounded-lg hover:bg-red-600 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm">Belum ada video.</p>
        @endif
    </div>

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor-deskripsi'))
            .catch(error => console.error(error));

        function addPhotoInput() {
            const container = document.getElementById('photo-container');
            const newInput = document.createElement('div');
            newInput.className = 'photo-input-group flex gap-2';
            newInput.innerHTML = `
                <input type="file" name="images[]" accept="image/jpeg,image/jpg,image/png,image/webp"
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
                <input type="text" name="youtube_urls[]"
                       class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                       placeholder="https://youtube.com/watch?v=...">
                <button type="button" onclick="this.parentElement.remove()"
                        class="px-4 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(newInput);
        }
    </script>

    <style>
        .ck-editor__editable {
            min-height: 350px;
        }
    </style>

@endsection
