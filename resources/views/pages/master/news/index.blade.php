@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between mb-3">
        <h4>Daftar Artikel</h4>
        <a href="{{ route('news.create') }}" class="btn btn-primary">Buat Artikel</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Author</th>
                        <th>Tanggal</th>
                        <th width="12%">Cover</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($articles as $i => $a)
                        <tr>
                            <td>{{ $articles->firstItem() + $i }}</td>

                            <td>
                                <a href="{{ route('news.show', $a->id) }}">
                                    {{ $a->title }}
                                </a>
                            </td>

                            <td>{{ ucfirst($a->category) }}</td>

                            <td>{{ $a->author->name }}</td>

                            <td>{{ $a->created_at->format('d M Y') }}</td>

                            <td>
                                @if($a->cover_url)
                                    <img src="{{ $a->cover_url }}" width="60" class="rounded shadow-sm">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>

                                <a href="{{ route('news.edit', $a->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('news.destroy', $a->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')

                                    <button onclick="return confirm('Hapus artikel ini?')" class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

            {{ $articles->links('pagination::bootstrap-4') }}

        </div>
    </div>

@endsection