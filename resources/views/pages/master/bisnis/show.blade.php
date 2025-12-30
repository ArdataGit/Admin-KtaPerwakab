@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Detail Bisnis</h4>
    <a href="{{ route('bisnis.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">

        {{-- INFO UTAMA --}}
        <table class="table table-bordered mb-4">
            <tr>
                <th width="25%">Nama Bisnis</th>
                <td>{{ $bisnis->nama }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>{{ $bisnis->kategori ?? '-' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if($bisnis->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Nonaktif</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Dibuat</th>
                <td>{{ $bisnis->created_at->format('d M Y H:i') }}</td>
            </tr>
        </table>
      
        <h5 class="mb-2">Kontak & Informasi</h5>

        <table class="table table-bordered mb-4">
            <tr>
                <th width="25%">Alamat</th>
                <td>{{ $bisnis->alamat ?? '-' }}</td>
            </tr>
            <tr>
                <th>Telepon</th>
                <td>
                    @if($bisnis->telepon)
                        <a href="tel:{{ $bisnis->telepon }}">{{ $bisnis->telepon }}</a>
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <th>Email</th>
                <td>
                    @if($bisnis->email)
                        <a href="mailto:{{ $bisnis->email }}">{{ $bisnis->email }}</a>
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <th>Website</th>
                <td>
                    @if($bisnis->website)
                        <a href="{{ $bisnis->website }}" target="_blank">
                            {{ $bisnis->website }}
                        </a>
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>


        {{-- DESKRIPSI --}}
        <h5 class="mb-2">Deskripsi</h5>
        <div class="border rounded p-3 mb-4 bg-light">
            {!! $bisnis->deskripsi ?? '<em>Tidak ada deskripsi</em>' !!}
        </div>

        {{-- FOTO --}}
        <h5 class="mb-3">Foto Bisnis</h5>

        @if($bisnis->images->count())
            <div class="row">
                @foreach($bisnis->images as $img)
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <img src="{{ asset('storage/'.$img->file_path) }}"
                                 class="card-img-top"
                                 style="height:160px;object-fit:cover">
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted">Belum ada foto.</p>
        @endif

        {{-- VIDEO --}}
        <h5 class="mt-4 mb-3">Video</h5>

        @if($bisnis->videos->count())
            <ul class="list-group">
                @foreach($bisnis->videos as $vid)
                    <li class="list-group-item">
                        <a href="{{ $vid->url }}" target="_blank">
                            {{ $vid->url }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">Belum ada video.</p>
        @endif

    </div>
</div>

<div class="d-flex gap-2">
    <a href="{{ route('bisnis.edit', $bisnis->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i> Edit
    </a>

    <form method="POST"
          action="{{ route('bisnis.destroy', $bisnis->id) }}"
          onsubmit="return confirm('Yakin ingin menghapus bisnis ini?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger">
            <i class="fas fa-trash"></i> Hapus
        </button>
    </form>
</div>

@endsection
