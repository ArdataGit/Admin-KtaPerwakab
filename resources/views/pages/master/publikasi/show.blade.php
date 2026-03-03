@extends('layouts.app')

@section('title', 'Detail Publikasi')

@section('page-title', 'Detail Publikasi')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Detail Publikasi</h2>
        <a href="{{ route('publikasi.index') }}"
           class="px-5 py-2.5 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-8">
            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $data->title }}</h1>

            <!-- Meta Info -->
            <div class="flex flex-wrap items-center gap-4 mb-6 pb-6 border-b border-gray-200">
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-user text-[#3E9A3E]"></i>
                    <span class="text-sm">Pembuat: <strong>{{ $data->creator }}</strong></span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-calendar text-[#3E9A3E]"></i>
                    <span class="text-sm">{{ $data->created_at->format('d M Y') }}</span>
                </div>
            </div>

            <!-- Description -->
            @if($data->description)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-align-left text-[#3E9A3E] mr-2"></i>Deskripsi
                    </h3>
                    <div class="text-gray-700 leading-relaxed whitespace-pre-line bg-gray-50 p-4 rounded-lg">
                        {{ $data->description }}
                    </div>
                </div>
            @endif

            <!-- Photos Section -->
            @if($data->photos->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-images text-[#3E9A3E] mr-2"></i>Foto ({{ $data->photos->count() }})
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($data->photos as $photo)
                            <a href="{{ asset('storage/' . $photo->file_path) }}" target="_blank" class="group">
                                <img src="{{ asset('storage/' . $photo->file_path) }}" 
                                     class="w-full h-48 object-cover rounded-lg shadow-md group-hover:shadow-xl transition-shadow"
                                     alt="Photo">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Videos Section -->
            @if($data->videos->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-video text-[#3E9A3E] mr-2"></i>Video ({{ $data->videos->count() }})
                    </h3>
                    <div class="space-y-2">
                        @foreach ($data->videos as $v)
                            <a href="{{ $v->link }}" target="_blank" 
                               class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                                <i class="fas fa-play-circle text-2xl text-[#3E9A3E] group-hover:text-[#2d7a2d]"></i>
                                <span class="text-[#3E9A3E] group-hover:underline flex-1 truncate">{{ $v->link }}</span>
                                <i class="fas fa-external-link-alt text-gray-400"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('publikasi.edit', $data->id) }}"
                   class="px-5 py-2.5 bg-yellow-500 text-white font-semibold rounded-lg shadow-md hover:bg-yellow-600 transition-all">
                    <i class="fas fa-edit mr-2"></i>Edit Publikasi
                </a>
                <form method="POST" action="{{ route('publikasi.destroy', $data->id) }}" class="inline-block"
                      onsubmit="return confirm('Yakin ingin menghapus publikasi ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2.5 bg-red-500 text-white font-semibold rounded-lg shadow-md hover:bg-red-600 transition-all">
                        <i class="fas fa-trash mr-2"></i>Hapus Publikasi
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection
