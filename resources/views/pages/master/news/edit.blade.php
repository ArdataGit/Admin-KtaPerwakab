@extends('layouts.app')

@section('title', 'Edit Artikel')

@section('page-title', 'Edit Artikel')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Artikel</h2>
        <a href="{{ route('news.index') }}"
           class="px-5 py-2.5 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <form action="{{ route('news.update', $article->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Judul -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $article->title) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                           placeholder="Masukkan judul artikel" required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20 appearance-none bg-white">
                        <option value="berita" {{ old('category', $article->category) == 'berita' ? 'selected' : '' }}>Berita</option>
                        <option value="pengumuman" {{ old('category', $article->category) == 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="lainnya" {{ old('category', $article->category) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link Video -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Link Video (Youtube / TikTok)
                    </label>
                    <input type="text" name="video_url" value="{{ old('video_url', $article->video_url) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                           placeholder="https://youtube.com/... atau https://tiktok.com/...">
                    @error('video_url')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konten -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Konten <span class="text-red-500">*</span>
                    </label>
                    <textarea id="summernote" name="content" class="w-full">{!! old('content', $article->content) !!}</textarea>
                    @error('content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cover Image -->
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Cover Baru
                    </label>
                    <input type="file" name="cover_image" accept="image/jpeg,image/jpg,image/png"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20">
                    <p class="text-gray-500 text-xs mt-1">Format: JPG, JPEG, PNG (Max: 2MB)</p>
                    @error('cover_image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Cover -->
                @if($article->cover_url)
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Cover Saat Ini
                        </label>
                        <img src="{{ $article->cover_url }}" 
                             class="w-32 h-32 object-cover rounded-lg shadow-md"
                             alt="Current Cover">
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-3 mt-6">
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                    <i class="fas fa-save mr-2"></i>Update Artikel
                </button>
                <a href="{{ route('news.index') }}"
                   class="px-6 py-2.5 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition-all">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#summernote').summernote({
                height: 300,
                placeholder: 'Tulis konten artikel...',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['font', ['fontsize', 'color']],
                    ['view', ['codeview']],
                ]
            });
        });
    </script>
@endpush