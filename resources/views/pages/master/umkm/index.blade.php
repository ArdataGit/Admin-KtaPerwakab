@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between mb-3">
        <h4>Daftar UMKM</h4>
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreateUmkm">
            Tambah UMKM
        </button>

    </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Nama UMKM</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th width="12%">Logo</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($umkms as $i => $umkm)
                        <tr>
                            <td>{{ $umkms->firstItem() + $i }}</td>

                            <td>
                                <strong>{{ $umkm->umkm_name }}</strong>
                            </td>

                            <td>{{ ucfirst($umkm->category) }}</td>

                            <td>{{ $umkm->location ?? '-' }}</td>

                            <td>
                                @if ($umkm->logo)
                                    <img src="{{ asset('storage/' . $umkm->logo) }}" width="60" class="rounded shadow-sm">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('umkm.products.index', $umkm->id) }}" class="btn btn-sm btn-info">
                                    Produk
                                </a>

                                <button class="btn btn-sm btn-warning" data-toggle="modal"
                                    data-target="#modalEditUmkm{{ $umkm->id }}">
                                    Edit
                                </button>


                                <form action="{{ route('umkm.destroy', $umkm->id) }}" method="POST"
                                    style="display:inline-block">
                                    @csrf
                                    @method('DELETE')

                                    <button onclick="return confirm('Hapus UMKM ini?')" class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- MODAL EDIT UMKM -->
                        <div class="modal fade" id="modalEditUmkm{{ $umkm->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="modalEditUmkmLabel{{ $umkm->id }}" aria-hidden="true">
                            <div class="modal-dialog  bg-white modal-lg" role="document">

                                <form action="{{ route('umkm.update', $umkm->id) }}" method="POST" enctype="multipart/form-data"
                                    class="modal-content">

                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalEditUmkmLabel{{ $umkm->id }}">
                                            Edit UMKM
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">

                                        {{-- Nama UMKM --}}
                                        <div class="form-group">
                                            <label>Nama UMKM</label>
                                            <input type="text" name="umkm_name" class="form-control"
                                                value="{{ old('umkm_name', $umkm->umkm_name) }}" required>
                                        </div>

                                        {{-- Kategori --}}
                                        <div class="form-group">
                                            <label>Kategori</label>
                                            <select name="category" class="form-control" required>
                                                <option value="">-- Pilih Kategori --</option>
                                                @php
                                                    $categories = [
                                                        'kuliner' => 'Kuliner',
                                                        'fashion' => 'Fashion',
                                                        'kerajinan' => 'Kerajinan',
                                                        'jasa' => 'Jasa',
                                                        'pertanian' => 'Pertanian',
                                                        'lainnya' => 'Lainnya',
                                                    ];
                                                @endphp

                                                @foreach ($categories as $key => $label)
                                                    <option value="{{ $key }}" {{ old('category', $umkm->category) === $key ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Lokasi --}}
                                        <div class="form-group">
                                            <label>Lokasi</label>
                                            <input type="text" name="location" class="form-control"
                                                value="{{ old('location', $umkm->location) }}">
                                        </div>

                                        {{-- WhatsApp --}}
                                        <div class="form-group">
                                            <label>WhatsApp</label>
                                            <input type="text" name="contact_wa" class="form-control"
                                                value="{{ old('contact_wa', $umkm->contact_wa) }}"
                                                placeholder="Contoh: 628123456789">
                                        </div>

                                        {{-- Logo --}}
                                        <div class="form-group">
                                            <label>Logo</label>
                                            <input type="file" name="logo" class="form-control-file">

                                            @if ($umkm->logo)
                                                <div class="mt-2">
                                                    <small class="text-muted d-block">Logo saat ini:</small>
                                                    <img src="{{ asset('storage/' . $umkm->logo) }}" width="60"
                                                        class="rounded shadow-sm mt-1">
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Deskripsi --}}
                                        <div class="form-group">
                                            <label>Deskripsi</label>
                                            <textarea name="description" class="form-control"
                                                rows="3">{{ old('description', $umkm->description) }}</textarea>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            Batal
                                        </button>
                                        <button type="submit" class="btn btn-warning">
                                            Update
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Data UMKM belum tersedia
                            </td>
                        </tr>

                    @endforelse
                </tbody>
            </table>

            {{ $umkms->links('pagination::bootstrap-4') }}

        </div>
    </div>
    <!-- MODAL CREATE UMKM -->
    <div class="modal fade" id="modalCreateUmkm" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('umkm.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah UMKM</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Nama UMKM</label>
                        <input type="text" name="umkm_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="kuliner">Kuliner</option>
                            <option value="fashion">Fashion</option>
                            <option value="kerajinan">Kerajinan</option>
                            <option value="jasa">Jasa</option>
                            <option value="pertanian">Pertanian</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label>Lokasi</label>
                        <input type="text" name="location" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>WhatsApp</label>
                        <input type="text" name="contact_wa" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Logo</label>
                        <input type="file" name="logo" class="form-control-file">
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>


@endsection