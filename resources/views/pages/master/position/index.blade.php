@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between my-3">
        <h4>Master Posisi</h4>
        <button class="btn btn-primary" data-toggle="modal" data-target="#createModal">Tambah Posisi</button>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th>Nama Posisi</th>
                <th width="20%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posisis as $i => $p)
                <tr>
                    <td>{{ $posisis->firstItem() + $i }}</td>
                    <td>{{ $p->name }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-toggle="modal"
                            data-target="#editModal{{ $p->id }}">Edit</button>
                        <form action="{{ route('posisi.destroy', $p->id) }}" method="POST" style="display:inline-block">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus data ini?')" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal{{ $p->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('posisi.update', $p->id) }}">
                                @csrf @method('PUT')
                                <div class="modal-header">
                                    <h5>Edit Posisi</h5>
                                </div>
                                <div class="modal-body">
                                    <input type="text" class="form-control" name="name" value="{{ $p->name }}" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>

    {{ $posisis->links('pagination::bootstrap-4') }}



    <!-- Create Modal -->
    <div class="modal fade" id="createModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('posisi.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5>Tambah Posisi</h5>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control" placeholder="Nama posisi" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
@endsection