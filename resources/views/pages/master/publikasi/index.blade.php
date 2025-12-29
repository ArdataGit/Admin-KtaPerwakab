@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('publikasi.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Publikasi
        </a>
        
        <form method="GET" action="{{ route('publikasi.index') }}" class="d-flex gap-2" style="max-width: 500px;">
            <input type="text" name="search" class="form-control" placeholder="Cari judul atau pembuat..." 
                   value="{{ request('search') }}" style="min-width: 250px;">
            <button type="submit" class="btn btn-secondary text-nowrap">
                <i class="fas fa-search"></i> Cari
            </button>
            @if(request('search'))
                <a href="{{ route('publikasi.index') }}" class="btn btn-outline-secondary text-nowrap">
                    <i class="fas fa-times"></i> Reset
                </a>
            @endif
        </form>
    </div>

    @if(request('search'))
        <div class="alert alert-info">
            Hasil pencarian untuk: <strong>{{ request('search') }}</strong> 
            ({{ $publikasi->total() }} data ditemukan)
        </div>
    @endif

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