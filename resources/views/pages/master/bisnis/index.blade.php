@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('bisnis.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Bisnis
    </a>

    <form method="GET" action="{{ route('bisnis.index') }}" class="d-flex gap-2" style="max-width: 500px;">
        <input type="text" name="search" class="form-control"
               placeholder="Cari nama atau kategori..."
               value="{{ request('search') }}" style="min-width: 250px;">
        <button type="submit" class="btn btn-secondary text-nowrap">
            <i class="fas fa-search"></i> Cari
        </button>
        @if(request('search'))
            <a href="{{ route('bisnis.index') }}" class="btn btn-outline-secondary text-nowrap">
                <i class="fas fa-times"></i> Reset
            </a>
        @endif
    </form>
</div>

@if(request('search'))
    <div class="alert alert-info">
        Hasil pencarian untuk: <strong>{{ request('search') }}</strong>
        ({{ $items->total() }} data ditemukan)
    </div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th width="5%">#</th>
            <th>Nama Bisnis</th>
            <th>Kategori</th>
            <th>Status</th>
            <th width="20%">Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($items as $i => $b)
            <tr>
                <td>{{ $items->firstItem() + $i }}</td>
                <td>
                    <strong>{{ $b->nama }}</strong><br>
                    <small class="text-muted">{{ $b->slug }}</small>
                </td>
                <td>{{ $b->kategori ?? '-' }}</td>
                <td>
                    @if($b->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('bisnis.show', $b->id) }}" class="btn btn-sm btn-info">Detail</a>
                    <a href="{{ route('bisnis.edit', $b->id) }}" class="btn btn-sm btn-warning">Edit</a>

                    <form method="POST"
                          action="{{ route('bisnis.destroy', $b->id) }}"
                          style="display:inline-block"
                          onsubmit="return confirm('Yakin ingin menghapus bisnis ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">
                    Data bisnis belum tersedia
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $items->links() }}

@endsection
