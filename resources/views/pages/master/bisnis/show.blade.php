@extends('layouts.app')

@section('title', 'Detail Bisnis')

@section('page-title', 'Detail Bisnis')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Detail Bisnis</h2>
        <a href="{{ route('bisnis.index') }}"
           class="px-5 py-2.5 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-8">
            <!-- Title & Status -->
            <div class="flex items-start justify-between mb-6 pb-6 border-b border-gray-200">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $bisnis->nama }}</h1>
                    @if($bisnis->kategori)
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                            {{ $bisnis->kategori }}
                        </span>
                    @endif
                </div>
                <div>
                    @if($bisnis->is_active)
                        <span class="px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i>Aktif
                        </span>
                    @else
                        <span class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium">
                            <i class="fas fa-times-circle mr-1"></i>Nonaktif
                        </span>
                    @endif
                </div>
            </div>

            <!-- Info Utama -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-info-circle text-[#3E9A3E] mr-2"></i>Informasi Dasar
                    </h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-3 gap-2 text-sm">
                            <span class="font-medium text-gray-600">Dibuat:</span>
                            <span class="col-span-2 text-gray-900">{{ $bisnis->created_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-address-book text-[#3E9A3E] mr-2"></i>Kontak
                    </h3>
                    <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                        @if($bisnis->telepon)
                            <div class="flex items-center gap-3">
                                <i class="fas fa-phone text-[#3E9A3E] w-5"></i>
                                <a href="tel:{{ $bisnis->telepon }}" class="text-[#3E9A3E] hover:underline">
                                    {{ $bisnis->telepon }}
                                </a>
                            </div>
                        @endif
                        @if($bisnis->email)
                            <div class="flex items-center gap-3">
                                <i class="fas fa-envelope text-[#3E9A3E] w-5"></i>
                                <a href="mailto:{{ $bisnis->email }}" class="text-[#3E9A3E] hover:underline">
                                    {{ $bisnis->email }}
                                </a>
                            </div>
                        @endif
                        @if($bisnis->website)
                            <div class="flex items-center gap-3">
                                <i class="fas fa-globe text-[#3E9A3E] w-5"></i>
                                <a href="{{ $bisnis->website }}" target="_blank" class="text-[#3E9A3E] hover:underline">
                                    {{ $bisnis->website }}
                                </a>
                            </div>
                        @endif
                        @if(!$bisnis->telepon && !$bisnis->email && !$bisnis->website)
                            <p class="text-gray-500 text-sm">Tidak ada kontak</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Alamat -->
            @if($bisnis->alamat)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-map-marker-alt text-[#3E9A3E] mr-2"></i>Alamat
                    </h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700">{{ $bisnis->alamat }}</p>
                    </div>
                </div>
            @endif

            <!-- Deskripsi -->
            @if($bisnis->deskripsi)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-align-left text-[#3E9A3E] mr-2"></i>Deskripsi
                    </h3>
                    <div class="prose max-w-none bg-gray-50 p-4 rounded-lg">
                        {!! $bisnis->deskripsi !!}
                    </div>
                </div>
            @endif

            <!-- Foto -->
            @if($bisnis->images->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-images text-[#3E9A3E] mr-2"></i>Foto Bisnis ({{ $bisnis->images->count() }})
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($bisnis->images as $img)
                            <a href="{{ asset('storage/'.$img->file_path) }}" target="_blank" class="group">
                                <img src="{{ asset('storage/'.$img->file_path) }}" 
                                     class="w-full h-48 object-cover rounded-lg shadow-md group-hover:shadow-xl transition-shadow"
                                     alt="Photo">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Video -->
            @if($bisnis->videos->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-video text-[#3E9A3E] mr-2"></i>Video ({{ $bisnis->videos->count() }})
                    </h3>
                    <div class="space-y-2">
                        @foreach($bisnis->videos as $vid)
                            <a href="{{ $vid->url }}" target="_blank" 
                               class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                                <i class="fas fa-play-circle text-2xl text-[#3E9A3E] group-hover:text-[#2d7a2d]"></i>
                                <span class="text-[#3E9A3E] group-hover:underline flex-1 truncate">{{ $vid->url }}</span>
                                <i class="fas fa-external-link-alt text-gray-400"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('bisnis.edit', $bisnis->id) }}"
                   class="px-5 py-2.5 bg-yellow-500 text-white font-semibold rounded-lg shadow-md hover:bg-yellow-600 transition-all">
                    <i class="fas fa-edit mr-2"></i>Edit Bisnis
                </a>
                <form method="POST" action="{{ route('bisnis.destroy', $bisnis->id) }}" class="inline-block"
                      onsubmit="return confirm('Yakin ingin menghapus bisnis ini?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="px-5 py-2.5 bg-red-500 text-white font-semibold rounded-lg shadow-md hover:bg-red-600 transition-all">
                        <i class="fas fa-trash mr-2"></i>Hapus Bisnis
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection
