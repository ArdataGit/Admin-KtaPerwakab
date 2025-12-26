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
                <th width="45%">Nama Kategori</th>
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

                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#massUserPointModal"
                            data-id="{{ $kategori->id }}" data-name="{{ $kategori->name }}" data-point="{{ $kategori->point }}">
                            Tambah Point User
                        </button>
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

    <div class="modal fade" id="massUserPointModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <form method="POST" action="{{ route('points.add-by-category') }}">
                    @csrf

                    <input type="hidden" name="point_kategori_id" id="kategori_id">

                    <div class="modal-header">
                        <h5>Tambah Point User</h5>
                    </div>

                    <div class="modal-body">

                        <div class="alert alert-info">
                            <strong>Kategori:</strong>
                            <span id="kategori_name"></span><br>
                            <strong>Point per User:</strong>
                            <span id="kategori_point"></span>
                        </div>

                        <div class="form-group">
                            <label>Pilih User</label>
                            <select name="users[]" id="user-select" class="form-control" multiple required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} ({{ $user->email }}) - Point: {{ $user->point }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                Ketik untuk mencari user, atau pilih multiple dengan CTRL / CMD
                            </small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success"
                            onclick="return confirm('Tambahkan point ke user terpilih?')">
                            Tambahkan Point
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>


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

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
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

        $('#massUserPointModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);

            $('#kategori_id').val(button.data('id'));
            $('#kategori_name').text(button.data('name'));
            $('#kategori_point').text(button.data('point'));
        });

        // Inisialisasi Select2 untuk select user
        $('#user-select').select2({
            placeholder: 'Pilih atau cari user',
            allowClear: true,
            width: '100%'
        });
    </script>
@endpush