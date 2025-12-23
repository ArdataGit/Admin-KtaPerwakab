@extends('layouts.app')

@section('content')

    <h4>Buat Artikel Baru</h4>

    <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mt-3">

            <div class="col-md-6 mb-3">
                <label>Judul</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Kategori</label>
                <select name="category" class="form-control">
                    <option value="berita">Berita</option>
                    <option value="pengumuman">Pengumuman</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div class="col-md-12 mb-3">
                <label>Link Video (Youtube / TikTok)</label>
                <input type="text" name="video_url" class="form-control"
                    placeholder="https://youtu.be/... atau https://www.tiktok.com/...">
            </div>


            <div class="col-md-12 mb-3">
                <label>Konten</label>
                <textarea name="content" id="summernote" class="form-control"></textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label>Cover Image</label>
                <input type="file" name="cover_image" class="form-control">
            </div>

        </div>

        <button class="btn btn-success">Simpan</button>

    </form>

@endsection


@push('scripts')
    <script>
        $(document).ready(function () {
            $('#summernote').summernote({
                height: 300,
                placeholder: 'Tulis konten artikel di sini...',
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