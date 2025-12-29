@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between mb-3">
        <h4>History Transaksi Donasi</h4>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            {{-- FILTER --}}
            <form method="GET" class="form-inline mb-3">
                <input
                    type="text"
                    name="keyword"
                    value="{{ request('keyword') }}"
                    class="form-control mr-2"
                    placeholder="Cari nama / email donatur">

                <select name="status" class="form-control mr-2">
                    <option value="">-- Semua Status --</option>
                    <option value="PAID" {{ request('status') === 'PAID' ? 'selected' : '' }}>PAID</option>
                    <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>PENDING</option>
                    <option value="UNPAID" {{ request('status') === 'UNPAID' ? 'selected' : '' }}>UNPAID</option>
                    <option value="EXPIRED" {{ request('status') === 'EXPIRED' ? 'selected' : '' }}>EXPIRED</option>
                </select>

                <button class="btn btn-primary btn-sm">
                    Filter
                </button>
            </form>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="4%">#</th>
                        <th>Campaign</th>
                        <th>Donatur</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($items as $i => $item)
                        <tr>
                            <td>{{ $items->firstItem() + $i }}</td>

                            <td>
                                <strong>{{ $item->campaign?->title }}</strong>
                            </td>

                            <td>
                                {{ $item->donor_name ?? '-' }}<br>
                                <small class="text-muted">
                                    {{ $item->donor_email ?? '-' }}
                                </small>
                            </td>

                            <td>
                                Rp {{ number_format($item->amount, 0, ',', '.') }}
                            </td>

                            <td>
                                @if ($item->status === 'PAID')
                                    <span class="badge badge-success">PAID</span>
                                @elseif ($item->status === 'PENDING')
                                    <span class="badge badge-warning">PENDING</span>
                                @elseif ($item->status === 'UNPAID')
                                    <span class="badge badge-info">UNPAID</span>
                                @else
                                    <span class="badge badge-secondary">{{ $item->status }}</span>
                                @endif
                            </td>

                            <td>
                                {{ $item->created_at->format('d M Y H:i') }}
                            </td>

                            <td>
                                <a href="{{ route('master.donation-transaction.show', $item->id) }}"
                                    class="btn btn-sm btn-info">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Data transaksi donasi belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $items->links('pagination::bootstrap-4') }}

        </div>
    </div>

@endsection
