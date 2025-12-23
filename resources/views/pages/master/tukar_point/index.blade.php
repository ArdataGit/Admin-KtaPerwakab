@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between my-3">
        <h4>Submenu Tukar Point</h4>
        <button class="btn btn-primary" data-toggle="modal" data-target="#createModal">
            Add Point
        </button>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('tukar-point.index') }}" class="mb-3">
        <div class="input-group">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Cari berdasarkan nama user..."
                value="{{ request('search') }}"
            >
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
                @if(request('search'))
                    <a href="{{ route('tukar-point.index') }}" class="btn btn-outline-danger">
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
                <th>User</th>
                <th>Produk Penukaran</th>
                <th width="12%">Point</th>
                <th width="15%">Tanggal</th>
                <th>Keterangan</th>
                <th width="20%">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $i => $item)
                <tr>
                    <td>{{ $items->firstItem() + $i }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>{{ $item->masterPenukaran->produk ?? '-' }}</td>
                    <td class="text-center">
                        <span class="text-danger">
                            {{ $item->point }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td>
                        <button
                            class="btn btn-sm btn-warning"
                            data-toggle="modal"
                            data-target="#editModal{{ $item->id }}">
                            Edit
                        </button>

                        <form
                            action="{{ route('tukar-point.destroy', $item->id) }}"
                            method="POST"
                            style="display:inline-block"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                onclick="return confirm('Hapus data ini?')"
                                class="btn btn-sm btn-danger">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>

                {{-- Edit Modal --}}
                <div class="modal fade" id="editModal{{ $item->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('tukar-point.update', $item->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5>Edit Tukar Point</h5>
                                </div>

                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>User</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            value="{{ $item->user->name }}"
                                            disabled
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label>Produk Penukaran</label>
                                        <select
                                            name="master_penukaran_poin_id"
                                            class="form-control select2"
                                            required
                                        >
                                            @foreach($produkPenukaran as $produk)
                                                <option
                                                    value="{{ $produk->id }}"
                                                    {{ $produk->id == $item->master_penukaran_poin_id ? 'selected' : '' }}
                                                >
                                                    {{ $produk->produk }} ({{ $produk->jumlah_poin }} poin)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Tanggal</label>
                                        <input
                                            type="date"
                                            name="tanggal"
                                            class="form-control"
                                            value="{{ $item->tanggal }}"
                                            required
                                        >
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
                    <td colspan="7" class="text-center">
                        Tidak ada data tukar point.
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
                <form method="POST" action="{{ route('tukar-point.store') }}">
                    @csrf

                    <div class="modal-header">
                        <h5>Add Tukar Point</h5>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label>User</label>
                            <select name="user_id" class="form-control select2" required>
                                <option value="">-- Pilih User --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} ({{ $user->point }} poin)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Produk Penukaran</label>
                            <select
                                name="master_penukaran_poin_id"
                                class="form-control select2"
                                required
                            >
                                <option value="">-- Pilih Produk --</option>
                                @foreach($produkPenukaran as $produk)
                                    <option value="{{ $produk->id }}">
                                        {{ $produk->produk }} ({{ $produk->jumlah_poin }} poin)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tanggal</label>
                            <input
                                type="date"
                                name="tanggal"
                                class="form-control"
                                required
                            >
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<link
    href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
    rel="stylesheet"
/>

<style>
    /* Paksa select2 full width */
    .select2-container {
        width: 100% !important;
    }

    /* Tinggi & padding seperti input bootstrap */
    .select2-container .select2-selection--single {
        height: 38px;
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    /* Posisi text di tengah */
    .select2-selection__rendered {
        line-height: 24px;
        padding-left: 0 !important;
    }

    /* Panah dropdown */
    .select2-selection__arrow {
        height: 38px;
        right: 8px;
    }

    /* Focus state */
    .select2-container--default.select2-container--focus
    .select2-selection--single {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
</style>
@endpush


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Cari dan pilih...",
                allowClear: true
            });
        });

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