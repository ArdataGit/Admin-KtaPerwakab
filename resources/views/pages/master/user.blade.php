@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between my-3">
        <h4>Master User</h4>
        <button class="btn btn-primary" data-toggle="modal" data-target="#createModal">Tambah User</button>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Role</th>
                <th>Status</th>
                <th width="10%">Foto</th>
                <th width="20%">Aksi</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($users as $i => $u)
                <tr>
                    <td>{{ $users->firstItem() + $i }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->phone }}</td>
                    <td>{{ ucfirst($u->role) }}</td>
                    <td>
                        <span class="badge badge-{{ $u->status == 'aktif' ? 'success' : 'secondary' }}">
                            {{ ucfirst($u->status) }}
                        </span>
                    </td>
                    <td>
                        @if($u->profile_photo_url)
                            <img src="{{ $u->profile_photo_url }}" class="rounded" width="40" height="40">
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal{{ $u->id }}">
                            Edit
                        </button>

                        <form action="{{ route('user.destroy', $u->id) }}" method="POST" style="display:inline-block">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus pengguna ini?')" class="btn btn-sm btn-danger">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>


                {{-- Edit Modal --}}
                <div class="modal fade" id="editModal{{ $u->id }}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('user.update', $u->id) }}" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="modal-header">
                                    <h5>Edit User</h5>
                                </div>
                                <div class="modal-body">

                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label>Nama</label>
                                            <input type="text" name="name" value="{{ $u->name }}" class="form-control" required>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label>Email</label>
                                            <input type="email" name="email" value="{{ $u->email }}" class="form-control"
                                                required>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label>Telepon</label>
                                            <input type="text" name="phone" value="{{ $u->phone }}" class="form-control">
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label>Jenis Kelamin</label>
                                            <select name="gender" class="form-control">
                                                <option value="">-</option>
                                                <option value="L" {{ $u->gender == 'L' ? 'selected' : '' }}>Laki laki</option>
                                                <option value="P" {{ $u->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="aktif" {{ $u->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                <option value="nonaktif" {{ $u->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label>Role</label>
                                            <select name="role" class="form-control" required>
                                                <option value="superadmin" {{ $u->role == 'superadmin' ? 'selected' : '' }}>
                                                    Superadmin</option>
                                                <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="pengurus" {{ $u->role == 'pengurus' ? 'selected' : '' }}>Pengurus
                                                </option>
                                                <option value="anggota" {{ $u->role == 'anggota' ? 'selected' : '' }}>Anggota
                                                </option>
                                                <option value="bendahara" {{ $u->role == 'bendahara' ? 'selected' : '' }}>
                                                    Bendahara</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label>Password (kosongkan jika tidak diubah)</label>
                                            <input type="password" name="password" class="form-control">
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label>Foto Profil</label>
                                            <input type="file" name="profile_photo" class="form-control">
                                        </div>

                                        <div class="col-12 mt-2">
                                            @if($u->profile_photo_url)
                                                <img src="{{ $u->profile_photo_url }}" width="70" class="rounded">
                                            @endif
                                        </div>

                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @endforeach
        </tbody>
    </table>

    {{ $users->links('pagination::bootstrap-4') }}


    {{-- Create Modal --}}
    <div class="modal fade" id="createModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5>Tambah User</h5>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Nama</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Telepon</label>
                                <input type="text" name="phone" class="form-control">
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Jenis Kelamin</label>
                                <select name="gender" class="form-control">
                                    <option value="">-</option>
                                    <option value="L">Laki laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Role</label>
                                <select name="role" class="form-control" required>
                                    <option value="superadmin">Superadmin</option>
                                    <option value="admin">Admin</option>
                                    <option value="pengurus">Pengurus</option>
                                    <option value="anggota">Anggota</option>
                                    <option value="bendahara">Bendahara</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Foto Profil</label>
                                <input type="file" name="profile_photo" class="form-control">
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
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
                title: 'Berhasil',
                text: '{{ session("success") }}',
                timer: 1500,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session("error") }}',
                timer: 1800,
                showConfirmButton: false
            });
        @endif
    </script>

@endsection