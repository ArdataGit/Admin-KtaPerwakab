@extends('layouts.app')

@section('title', 'Sejarah Organisasi')

@section('page-title', 'Sejarah Organisasi')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Kelola Sejarah Organisasi</h2>

        <button onclick="document.getElementById('historyForm').submit()"
            class="px-4 py-2 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2">
            <i class="fas fa-save text-sm"></i>
            Simpan Perubahan
        </button>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-xl shadow-md p-6">

        <form id="historyForm"
              action="{{ route('organization.history.store') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <!-- Judul -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Halaman
                </label>
                <input type="text"
                       name="title"
                       value="{{ old('title', $history->title ?? '') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                       placeholder="Contoh: Sejarah Berdirinya Organisasi">
            </div>

            <!-- Meta Description -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Meta Description (SEO)
                </label>
                <textarea name="meta_description"
                          rows="2"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                          placeholder="Deskripsi singkat untuk SEO...">{{ old('meta_description', $history->meta_description ?? '') }}</textarea>
            </div>

            <!-- Featured Image -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Gambar Utama
                </label>

                <input type="file"
                       name="featured_image"
                       accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">

                @if(isset($history) && $history->featured_image)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-2">Gambar Saat Ini:</p>
                        <img src="{{ asset('storage/'.$history->featured_image) }}"
                             class="w-48 rounded-lg shadow border border-gray-200">
                    </div>
                @endif
            </div>

            <!-- Content Editor -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Isi Sejarah
                </label>

                <textarea id="editor"
                          name="content"
                          rows="10"
                          class="w-full border border-gray-300 rounded-lg">
                    {{ old('content', $history->content ?? '') }}
                </textarea>
            </div>

        </form>

    </div>

@endsection


@push('scripts')

<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/zvffqh7jgnjj2h4r82iobtthnkoyd6hw1zvvt9c33ktqq6r3/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

<script>
    tinymce.init({
        selector: '#editor',
        height: 500,
        menubar: true,
        plugins: [
            'advlist autolink lists link image charmap preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table help wordcount'
        ],
        toolbar:
            'undo redo | blocks | bold italic forecolor | ' +
            'alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | image media | removeformat | help',

        content_style: 'body { font-family:Arial,Helvetica,sans-serif; font-size:14px }'
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session("success") }}',
        timer: 1500,
        showConfirmButton: false
    });
</script>
@endif

@endpush