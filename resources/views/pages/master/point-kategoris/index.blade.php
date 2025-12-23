@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between my-3">
        <h4>Master Point Kategori</h4>
        <button class="btn btn-primary" data-toggle="modal" data-target="#createModal">Tambah Kategori</button>
    </div>

    <!-- Search Form (Opsional, untuk persist search) -->
    <form method="GET" action="{{ route('point-kategoris.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama kategori..."
                value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
                @if(request('search'))
                    <a href="{{ route('point-kategoris.index') }}" class="btn btn-outline-danger">Reset</a>
                @endif
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th>Nama Kategori</th>
                <th width="10%">Point</th>
                <th width="20%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pointKategoris as $i => $kategori)
                <tr>
                    <td>{{ $pointKategoris->firstItem() + $i }}</td>
                    <td>{{ $kategori->name }}</td>
                    <td>{{ $kategori->point }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-toggle="modal"
                            data-target="#editModal{{ $kategori->id }}">Edit</button>

                        <form action="{{ route('point-kategoris.destroy', $kategori->id) }}" method="POST"
                            style="display:inline-block">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus data ini?')" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal{{ $kategori->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('point-kategoris.update', $kategori->id) }}">
                                @csrf @method('PUT')
                                <div class="modal-header">
                                    <h5>Edit Kategori</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama Kategori</label>
                                        <input type="text" class="form-control" name="name" value="{{ $kategori->name }}"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label>Point</label>
                                        <input type="number" class="form-control" name="point" value="{{ $kategori->point }}"
                                            min="0" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $pointKategoris->links('pagination::bootstrap-4') }}

    <!-- Create Modal -->
    <div class="modal fade" id="createModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('point-kategoris.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5>Tambah Kategori</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Kategori</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Kategori" required>
                        </div>
                        <div class="form-group">
                            <label>Point</label>
                            <input type="number" name="point" class="form-control" placeholder="Jumlah Point" min="0"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Tambah</button>
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