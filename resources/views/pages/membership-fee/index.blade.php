@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between mb-3">
        <h4>Daftar Iuran Anggota</h4>
    </div>

    {{-- FILTER --}}
    <div class="card shadow mb-3">
        <div class="card-body">

            <form method="GET" class="row g-2 align-items-end">

                <div class="col-md-4">
                    <label class="form-label text-muted small">Cari Anggota</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama atau Email"
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted small">Status</label>
                    <select name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted small">Jenis</label>
                    <select name="type" class="form-control">
                        <option value="">Semua Jenis</option>
                        <option value="bulanan" {{ request('type') === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="tahunan" {{ request('type') === 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>

            </form>

        </div>
    </div>

    {{-- TABLE --}}
    <div class="card shadow mb-4">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Anggota</th>
                        <th>Email</th>
                        <th>Jenis</th>
                        <th>Nominal</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($fees as $i => $fee)
                        <tr>
                            <td>{{ $fees->firstItem() + $i }}</td>

                            <td>{{ $fee->user->name }}</td>

                            <td>{{ $fee->user->email }}</td>

                            <td class="text-capitalize">{{ $fee->type }}</td>

                            <td>
                                Rp{{ number_format($fee->amount, 0, ',', '.') }}
                            </td>

                            {{-- BUKTI --}}
                            <td class="text-center">
                                @if ($fee->proof_image)
                                    <a href="{{ asset('storage/' . $fee->proof_image) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $fee->proof_image) }}" width="50" class="rounded shadow-sm"
                                            alt="Bukti Bayar">
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if ($fee->payment_status === 'success')
                                    <span class="badge bg-success">Success</span>
                                @elseif ($fee->payment_status === 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>

                            <td>
                                {{ $fee->created_at->format('d M Y') }}
                            </td>

                            {{-- AKSI --}}
                            <td>

                                <!-- <a href="{{ route('membership-fee.show', $fee->id) }}" class="btn btn-sm btn-info mb-1">
                                    Detail
                                </a> -->

                                @if ($fee->payment_status === 'pending')
                                    <form action="{{ route('membership-fee.validate', $fee->id) }}" method="POST"
                                        style="display:inline-block">
                                        @csrf
                                        <input type="hidden" name="payment_status" value="success">
                                        <button class="btn btn-sm btn-success" onclick="return confirm('Setujui pembayaran ini?')">
                                            Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('membership-fee.validate', $fee->id) }}" method="POST"
                                        style="display:inline-block">
                                        @csrf
                                        <input type="hidden" name="payment_status" value="failed">
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Tolak pembayaran ini?')">
                                            Reject
                                        </button>
                                    </form>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                Belum ada data iuran
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $fees->links('pagination::bootstrap-4') }}

        </div>
    </div>

@endsection