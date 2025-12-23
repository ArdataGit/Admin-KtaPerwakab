@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between my-3">
    <h4>Master Divisi</h4>
    <button class="btn btn-primary" data-toggle="modal" data-target="#createModal">Tambah Divisi</button>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th width="5%">#</th>
            <th>Nama Divisi</th>
            <th width="20%">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($divisions as $i => $d)
        <tr>
            <td>{{ $divisions->firstItem() + $i }}</td>
            <td>{{ $d->name }}</td>
            <td>
                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal{{ $d->id }}">Edit</button>

                <form action="{{ route('divisi.destroy', $d->id) }}" method="POST" style="display:inline-block">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Hapus data ini?')" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal{{ $d->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('divisi.update', $d->id) }}">
                        @csrf @method('PUT')
                        <div class="modal-header"><h5>Edit Divisi</h5></div>
                        <div class="modal-body">
                            <input type="text" class="form-control" name="name" value="{{ $d->name }}" required>
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

{{ $divisions->links('pagination::bootstrap-4') }}


<!-- Create Modal -->
<div class="modal fade" id="createModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('divisi.store') }}">
                @csrf
                <div class="modal-header"><h5>Tambah Divisi</h5></div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control" placeholder="Nama Divisi" required>
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
