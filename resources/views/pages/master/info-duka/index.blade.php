@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between mb-3">
        <h4>Daftar Info Duka</h4>
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreateInfoDuka">
            Tambah Info Duka
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Nama Almarhum</th>
                        <th>Judul</th>
                        <th>Tanggal Wafat</th>
                        <th>Status</th>
                        <th width="10%">Foto</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($items as $i => $item)
                        <tr>
                            <td>{{ $items->firstItem() + $i }}</td>

                            <td>
                                <strong>{{ $item->nama_almarhum }}</strong><br>
                                <small class="text-muted">
                                    {{ $item->usia ? $item->usia . ' th' : '-' }} • {{ $item->asal ?? '-' }}
                                </small>
                            </td>

                            <td>{{ $item->judul }}</td>

                            <td>{{ $item->tanggal_wafat->format('d M Y') }}</td>

                            <td>
                                @if ($item->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Nonaktif</span>
                                @endif
                            </td>

                            <td>
                                @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" width="60" class="rounded shadow-sm">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                <button class="btn btn-sm btn-warning" data-toggle="modal"
                                    data-target="#modalEditInfoDuka{{ $item->id }}">
                                    Edit
                                </button>

                                <form action="{{ route('info-duka.destroy', $item->id) }}" method="POST"
                                    style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Hapus info duka ini?')" class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- MODAL EDIT --}}
                        <div class="modal fade" id="modalEditInfoDuka{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg  bg-white">
                                <form action="{{ route('info-duka.update', $item->id) }}" method="POST"
                                    enctype="multipart/form-data" class="modal-content">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Info Duka</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">

                                        @include('pages.master.info-duka.form', ['data' => $item])

                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button class="btn btn-warning">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Data Info Duka belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $items->links('pagination::bootstrap-4') }}

        </div>
    </div>

    {{-- MODAL CREATE --}}
    <div class="modal fade" id="modalCreateInfoDuka" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('info-duka.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Info Duka</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    @include('pages.master.info-duka.form', ['data' => null])

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>

@endsection