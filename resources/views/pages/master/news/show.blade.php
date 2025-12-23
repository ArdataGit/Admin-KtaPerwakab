@extends('layouts.app')

@section('content')

    <div class="card shadow mb-4">
        <div class="card-body">

            <h3>{{ $article->title }}</h3>

            <div class="text-muted mb-3">
                <small>
                    Kategori: <b>{{ ucfirst($article->category) }}</b> |
                    Author: <b>{{ $article->author->name }}</b> |
                    Tanggal: {{ $article->created_at->format('d M Y') }}
                </small>
            </div>

            @if($article->cover_url)
                <img src="{{ $article->cover_url }}" class="img-fluid mb-4 rounded shadow"
                    style="max-height: 300px; object-fit: cover;">
            @endif

            @if($article->video_url)
                <div class="col-md-12 mb-3">
                    <label>Preview Video</label>
                    <br>

                    @if(str_contains($article->video_url, 'youtube') || str_contains($article->video_url, 'youtu.be'))
                        <iframe width="400" height="225"
                            src="https://www.youtube.com/embed/{{ \Illuminate\Support\Str::afterLast($article->video_url, '/') }}"
                            frameborder="0" allowfullscreen>
                        </iframe>
                    @endif

                    @if(str_contains($article->video_url, 'tiktok'))
                        <blockquote class="tiktok-embed" cite="{{ $article->video_url }}" data-video-id="">
                            <a href="{{ $article->video_url }}">Lihat di TikTok</a>
                        </blockquote>
                        <script async src="https://www.tiktok.com/embed.js"></script>
                    @endif
                </div>
            @endif


            <div class="content">
                {!! $article->content !!}
            </div>

            <a href="{{ route('news.index') }}" class="btn btn-secondary mt-4">
                Kembali
            </a>

        </div>
    </div>

@endsection