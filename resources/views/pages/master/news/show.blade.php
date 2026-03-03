@extends('layouts.app')

@section('title', 'Detail Artikel')

@section('page-title', 'Detail Artikel')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Detail Artikel</h2>
        <a href="{{ route('news.index') }}"
           class="px-5 py-2.5 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Article Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Cover Image -->
        @if($article->cover_url)
            <div class="w-full h-80 overflow-hidden">
                <img src="{{ $article->cover_url }}" 
                     class="w-full h-full object-cover"
                     alt="{{ $article->title }}">
            </div>
        @endif

        <!-- Content -->
        <div class="p-8">
            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $article->title }}</h1>

            <!-- Meta Info -->
            <div class="flex flex-wrap items-center gap-4 mb-6 pb-6 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <i class="fas fa-tag text-[#3E9A3E]"></i>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium capitalize">
                        {{ $article->category }}
                    </span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-user text-[#3E9A3E]"></i>
                    <span class="text-sm">{{ $article->author->name }}</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                    <i class="fas fa-calendar text-[#3E9A3E]"></i>
                    <span class="text-sm">{{ $article->created_at->format('d M Y') }}</span>
                </div>
            </div>

            <!-- Video Section -->
            @if($article->video_url)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                        <i class="fas fa-video text-[#3E9A3E] mr-2"></i>Video
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        @if(str_contains($article->video_url, 'youtube') || str_contains($article->video_url, 'youtu.be'))
                            <div class="aspect-video">
                                <iframe 
                                    class="w-full h-full rounded-lg"
                                    src="https://www.youtube.com/embed/{{ \Illuminate\Support\Str::afterLast($article->video_url, '/') }}"
                                    frameborder="0" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                        @elseif(str_contains($article->video_url, 'tiktok'))
                            <blockquote class="tiktok-embed" cite="{{ $article->video_url }}" data-video-id="">
                                <a href="{{ $article->video_url }}" target="_blank" 
                                   class="text-[#3E9A3E] hover:underline">
                                    <i class="fab fa-tiktok mr-2"></i>Lihat di TikTok
                                </a>
                            </blockquote>
                            <script async src="https://www.tiktok.com/embed.js"></script>
                        @else
                            <a href="{{ $article->video_url }}" target="_blank" 
                               class="text-[#3E9A3E] hover:underline">
                                <i class="fas fa-external-link-alt mr-2"></i>{{ $article->video_url }}
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Article Content -->
            <div class="prose max-w-none">
                <div class="text-gray-700 leading-relaxed">
                    {!! $article->content !!}
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('news.edit', $article->id) }}"
                   class="px-5 py-2.5 bg-yellow-500 text-white font-semibold rounded-lg shadow-md hover:bg-yellow-600 transition-all">
                    <i class="fas fa-edit mr-2"></i>Edit Artikel
                </a>
                <form action="{{ route('news.destroy', $article->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Hapus artikel ini?')"
                            class="px-5 py-2.5 bg-red-500 text-white font-semibold rounded-lg shadow-md hover:bg-red-600 transition-all">
                        <i class="fas fa-trash mr-2"></i>Hapus Artikel
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection
