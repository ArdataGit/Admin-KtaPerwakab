@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between my-3">
        <h4>Master Produk Penukaran Poin</h4>
        <button class="btn btn-primary" data-toggle="modal" data-target="#createModal">
            Tambah Produk
        </button>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('penukaran-poin.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama produk..."
                value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
                @if(request('search'))
                    <a href="{{ route('penukaran-poin.index') }}" class="btn btn-outline-danger">
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th>Produk</th>
                <th>Keterangan</th>
                <th width="10%">Image</th>
                <th width="10%">Jumlah Poin</th>
                <th width="20%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $i => $item)
                <tr>
                    <td>{{ $items->firstItem() + $i }}</td>
                    <td>{{ $item->produk }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                    <td class="text-center">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="image" style="width:60px;height:auto;">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->jumlah_poin }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal{{ $item->id }}">
                            Edit
                        </button>

                        <form action="{{ route('penukaran-poin.destroy', $item->id) }}" method="POST"
                            style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Hapus data ini?')" class="btn btn-sm btn-danger">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>

                {{-- Edit Modal --}}
                <div class="modal fade" id="editModal{{ $item->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('penukaran-poin.update', $item->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5>Edit Produk Penukaran</h5>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Produk</label>
                                        <input type="text" class="form-control" name="produk" value="{{ $item->produk }}"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label>Keterangan</label>
                                        <textarea class="form-control" name="keterangan"
                                            rows="3">{{ $item->keterangan }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Jumlah Poin</label>
                                        <input type="number" class="form-control" name="jumlah_poin"
                                            value="{{ $item->jumlah_poin }}" min="1" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Image</label>
                                        <input type="file" name="image" class="form-control">
                                        @if($item->image)
                                            <small class="text-muted">
                                                Gambar saat ini akan diganti jika upload baru
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        Batal
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        Tidak ada data ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $items->links('pagination::bootstrap-4') }}

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('penukaran-poin.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header">
                        <h5>Tambah Produk Penukaran</h5>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Produk</label>
                            <input type="text" name="produk" class="form-control" placeholder="Nama Produk" required>
                        </div>

                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3"
                                placeholder="Berlaku s/d, syarat, dll"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Poin</label>
                            <input type="number" name="jumlah_poin" class="form-control" placeholder="Jumlah Poin" min="1"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                timer: 1800,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session("error") }}',
                timer: 2000,
                showConfirmButton: false
            });
        @endif
    </script>
@endpush