@extends('layouts.app')

@section('content')

    <h4>Edit Artikel</h4>

    <form action="{{ route('news.update', $article->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row mt-3">

            <div class="col-md-6 mb-3">
                <label>Judul</label>
                <input type="text" name="title" value="{{ $article->title }}" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Kategori</label>
                <select name="category" class="form-control">
                    <option value="berita" {{ $article->category == 'berita' ? 'selected' : '' }}>Berita</option>
                    <option value="pengumuman" {{ $article->category == 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                    <option value="lainnya" {{ $article->category == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label>Link Video (Youtube / TikTok)</label>
                <input type="text" name="video_url" value="{{ $article->video_url }}" class="form-control">
            </div>


            <div class="col-md-12 mb-3">
                <label>Konten</label>
                <textarea id="summernote" name="content" class="form-control">{!! $article->content !!}</textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label>Cover Baru</label>
                <input type="file" name="cover_image" class="form-control">
                <small class="text-muted">
                    Format didukung: JPG, JPEG, PNG
                </small>
            </div>

            @if($article->cover_url)
                <div class="col-md-6 mb-3">
                    <label>Cover Saat Ini</label><br>
                    <img src="{{ $article->cover_url }}" width="100" class="rounded shadow-sm">
                </div>
            @endif

        </div>

        <button class="btn btn-primary">Update</button>

    </form>

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