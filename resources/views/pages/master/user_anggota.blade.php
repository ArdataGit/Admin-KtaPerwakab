@extends('layouts.app')
@section('content')
    <div class="d-flex justify-content-between my-3">
        <h4>Master User (Anggota)</h4>
        <button class="btn btn-primary" data-toggle="modal" data-target="#createModal">Tambah User</button>
    </div>
    {{-- Search Bar (Selalu Visible) --}}
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                placeholder="Cari nama, email, atau telepon...">
            <div class="input-group-append">
                <button class="btn btn-outline-primary" type="submit">Cari</button>
                <a href="{{ route('user.anggota') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
        @if(request('search'))
            <small class="text-muted">Hasil pencarian untuk: "{{ request('search') }}"</small>
        @endif
    </form>
    {{-- Advanced Filter (Default Hide, Toggle Button) --}}
    <div class="mb-3">
        <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#advancedFilter"
            aria-expanded="false" aria-controls="advancedFilter">
            Filter Lanjutan <i class="fas fa-chevron-down ml-1"></i>
        </button>
    </div>
    <form method="GET" id="advancedFilter"
        class="collapse mb-3 {{ request()->hasAny(['usia_min', 'usia_max', 'city', 'status', 'tahun_join']) ? 'show' : '' }}">
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif
        <div class="row align-items-end">
            <div class="col-md-2">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="">Semua</option>
                    <option value="anggota" {{ request('role') == 'anggota' ? 'selected' : '' }}>Anggota</option>
                    <option value="publik" {{ request('role') == 'publik' ? 'selected' : '' }}>Publik</option>
                </select>
            </div>

            <div class="col-md-2">
                <label>Usia</label>
                <div class="d-flex gap-1">
                    <input type="number" name="usia_min" value="{{ request('usia_min') }}" class="form-control"
                        placeholder="Min">
                    <input type="number" name="usia_max" value="{{ request('usia_max') }}" class="form-control"
                        placeholder="Max">
                </div>
            </div>

            <div class="col-md-2">
                <label>Kota</label>
                <input type="text" name="city" value="{{ request('city') }}" class="form-control" placeholder="Domisili">
            </div>

            <div class="col-md-2">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">Semua</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="col-md-2">
                <label>Tahun Join</label>
                <input type="number" name="tahun_join" value="{{ request('tahun_join') }}" class="form-control">
            </div>

            <div class="col-md-1">
                <button class="btn btn-primary w-100">Filter</button>
            </div>

            <div class="col-md-1">
                <a href="{{ route('user.anggota') }}@if(request('search'))?search={{ request('search') }}@endif"
                    class="btn btn-secondary w-100">Reset</a>
            </div>
        </div>

    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>Role</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Jenis Kelamin</th>
                    <th>Tgl Lahir</th>
                    <th>Usia</th>
                    <th width="8%">Point</th>
                    <th>Kota</th>
                    <th>Pekerjaan</th>
                    <th>Tanggal Bergabung</th>
                    <th>Status</th>
                    <th width="8%">Foto</th>
                    <th>Tanggal Expired</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $i => $u)
                    <tr>
                        <td>{{ $users->firstItem() + $i }}</td>
                        <td>
                            <span class="badge badge-{{ $u->role == 'anggota' ? 'primary' : 'secondary' }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>

                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->phone ?? '-' }}</td>
                        <td>{{ $u->gender == 'L' ? 'Laki-laki' : ($u->gender == 'P' ? 'Perempuan' : '-') }}</td>
                        <td>
                            {{ $u->birth_date ? \Carbon\Carbon::parse($u->birth_date)->translatedFormat('d F Y') : '-' }}
                        </td>
                        <td>{{ $u->age ? $u->age . ' Tahun' : '-' }}</td>
                        <td>
                            <span class="badge badge-info">{{ $u->point ?? 0 }}</span>
                        </td>
                        <td>{{ $u->city ?? '-' }}</td>
                        <td>{{ $u->occupation ?? '-' }}</td>
                        <td>
                            {{ $u->join_date ? \Carbon\Carbon::parse($u->join_date)->translatedFormat('d F Y') : '-' }}
                        </td>
                        <td>
                            <span class="badge badge-{{ $u->status == 'aktif' ? 'success' : 'secondary' }}">
                                {{ ucfirst($u->status) }}
                            </span>
                        </td>
                        <td>
                            @if($u->profile_photo_url)
                                <img src="{{ $u->profile_photo_url }}" class="rounded" width="40" height="40">
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            {{ $u->expired_at ? \Carbon\Carbon::parse($u->expired_at)->translatedFormat('d F Y') : '-' }}
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal{{ $u->id }}">
                                Edit
                            </button>
                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#historyModal{{ $u->id }}">
                                Add Kegiatan
                            </button>
                            <form action="{{ route('user.destroy', $u->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    {{-- Modal Edit --}}
                    <div class="modal fade" id="editModal{{ $u->id }}">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('user.update', $u->id) }}" enctype="multipart/form-data">
                                    @csrf @method('PUT')
                                    <div class="modal-header">
                                        <h5>Edit User (Anggota)</h5>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label>Nama</label>
                                                <input type="text" name="name" value="{{ $u->name }}" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label>Email</label>
                                                <input type="email" name="email" value="{{ $u->email }}" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label>Telepon</label>
                                                <input type="text" name="phone" value="{{ $u->phone }}" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label>Jenis Kelamin</label>
                                                <select name="gender" class="form-control">
                                                    <option value="">-</option>
                                                    <option value="L" {{ $u->gender == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                    <option value="P" {{ $u->gender == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label>Tanggal Lahir</label>
                                                <input type="date" name="birth_date" value="{{ $u->birth_date }}"
                                                    class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label>Kota</label>
                                                <input type="text" name="city" value="{{ $u->city }}" class="form-control">
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label>Alamat</label>
                                                <textarea name="address" class="form-control"
                                                    rows="2">{{ $u->address }}</textarea>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label>Pekerjaan</label>
                                                <input type="text" name="occupation" value="{{ $u->occupation }}"
                                                    class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label>Role</label>
                                                <select name="role" class="form-control">
                                                    <option value="anggota" {{ $u->role == 'anggota' ? 'selected' : '' }}>Anggota
                                                    </option>
                                                    <option value="publik" {{ $u->role == 'publik' ? 'selected' : '' }}>Publik
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label>Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="aktif" {{ $u->status == 'aktif' ? 'selected' : '' }}>Aktif
                                                    </option>
                                                    <option value="nonaktif" {{ $u->status == 'nonaktif' ? 'selected' : '' }}>
                                                        Nonaktif
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label>Password (opsional)</label>
                                                <input type="password" name="password" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label>Foto Profil</label>
                                                <input type="file" name="profile_photo" class="form-control">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                @if ($u->profile_photo_url)
                                                    <label>Foto Sekarang</label><br>
                                                    <img src="{{ $u->profile_photo_url }}" width="70" class="rounded border">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- Modal Add Kegiatan --}}
                    <div class="modal fade" id="historyModal{{ $u->id }}">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5>Kelola Point - {{ $u->name }}</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <h6>Total Point Saat Ini: <span class="badge badge-info">{{ $u->point ?? 0 }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                    {{-- Form Tambah Kegiatan --}}
                                    <form method="POST" action="{{ route('user.add-point', $u->id) }}" class="mb-4">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-8">
                                                <label>Pilih Kegiatan (Kategori Point)</label>
                                                <select name="id_category" class="form-control" required>
                                                    <option value="">-- Pilih Kategori --</option>
                                                    @foreach(\App\Models\PointKategori::all() as $kategori)
                                                        <option value="{{ $kategori->id }}">{{ $kategori->name }}
                                                            ({{ $kategori->point }} Point)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>&nbsp;</label><br>
                                                <button type="submit" class="btn btn-success">Tambah Kegiatan</button>
                                            </div>
                                        </div>
                                    </form>
                                    {{-- History Point --}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6>Riwayat Point</h6>
                                            @forelse($u->userPoints()->with('pointKategori')->orderBy('created_at', 'desc')->get() as $point)
                                                <div class="card mb-2">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <strong>{{ $point->pointKategori->name ?? 'Kategori Tidak Ditemukan' }}</strong><br>
                                                                <small class="text-muted">Point:
                                                                    +{{ $point->pointKategori->point ?? 0 }}</small>
                                                            </div>
                                                            <div class="text-right">
                                                                <small class="text-muted">
                                                                    {{ \Carbon\Carbon::parse($point->created_at)->translatedFormat('d F Y H:i') }}<br>
                                                                    Ditambahkan oleh: {{ $point->createdBy->name ?? 'Sistem' }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-muted">Belum ada riwayat point untuk user ini.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="14" class="text-center">Tidak ada data anggota.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $users->links('pagination::bootstrap-4') }}
    {{-- Create Modal --}}
    <div class="modal fade" id="createModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5>Tambah User (Anggota)</h5>
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
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Tanggal Lahir</label>
                                <input type="date" name="birth_date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Kota</label>
                                <input type="text" name="city" class="form-control">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Alamat</label>
                                <textarea name="address" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Pekerjaan</label>
                                <input type="text" name="occupation" class="form-control">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label>Role</label>
                                <select name="role" class="form-control" required>
                                    <option value="anggota">Anggota</option>
                                    <option value="publik">Publik</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
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
                        <button class="btn btn-success">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- SweetAlert Success --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 1600
            });
        </script>
    @endif
    {{-- SweetAlert untuk tambah point (opsional, jika redirect dengan session) --}}
    @if(session('point_success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Point Ditambahkan',
                text: "{{ session('point_success') }}",
                showConfirmButton: false,
                timer: 1600
            });
        </script>
    @endif
@endsection