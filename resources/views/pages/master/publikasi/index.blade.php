@extends('layouts.app')

@section('content')

    <a href="{{ route('publikasi.create') }}" class="btn btn-primary mb-3">Tambah Publikasi</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Judul</th>
                <th>Dibuat Oleh</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($publikasi as $i => $p)
                <tr>
                    <td>{{ $publikasi->firstItem() + $i }}</td>
                    <td>{{ $p->title }}</td>
                    <td>{{ $p->creator }}</td>
                    <td>{{ $p->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('publikasi.show', $p->id) }}" class="btn btn-sm btn-info">Detail</a>
                        <a href="{{ route('publikasi.edit', $p->id) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form method="POST" action="{{ route('publikasi.destroy', $p->id) }}" style="display:inline-block"
                            onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $publikasi->links() }}

@endsection