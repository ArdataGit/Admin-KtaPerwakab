@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between mb-3">
        <h4>Detail Transaksi Donasi</h4>

        <a href="{{ route('master.donation-transaction.index') }}"
            class="btn btn-secondary btn-sm">
            Kembali
        </a>
    </div>

    <div class="row">

        {{-- INFO DONASI --}}
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header font-weight-bold">
                    Informasi Donasi
                </div>

                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Campaign</th>
                            <td>{{ $donation->campaign?->title }}</td>
                        </tr>
                        <tr>
                            <th>Nominal</th>
                            <td>
                                Rp {{ number_format($donation->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if ($donation->status === 'PAID')
                                    <span class="badge badge-success">PAID</span>
                                @elseif ($donation->status === 'PENDING')
                                    <span class="badge badge-warning">PENDING</span>
                                @else
                                    <span class="badge badge-secondary">{{ $donation->status }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>{{ $donation->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- INFO DONATUR --}}
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header font-weight-bold">
                    Informasi Donatur
                </div>

                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Nama</th>
                            <td>{{ $donation->donor_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $donation->donor_email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>No. HP</th>
                            <td>{{ $donation->donor_phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>User</th>
                            <td>
                                {{ $donation->user?->name ?? '-' }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- INFO PEMBAYARAN --}}
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header font-weight-bold">
                    Informasi Pembayaran (Tripay)
                </div>

                <div class="card-body">
                    @if ($donation->tripayTransaction)
                        <table class="table table-sm">
                            <tr>
                                <th width="25%">Reference</th>
                                <td>{{ $donation->tripayTransaction->tripay_reference }}</td>
                            </tr>
                            <tr>
                                <th>Metode</th>
                                <td>{{ $donation->tripayTransaction->payment_name }}</td>
                            </tr>
                            <tr>
                                <th>Kode Bayar / VA</th>
                                <td>
                                    {{ data_get($donation->tripayTransaction->tripay_payload, 'data.pay_code') ?? '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Expired</th>
                                <td>
                                    {{ optional($donation->tripayTransaction->expired_at)->format('d M Y H:i') }}
                                </td>
                            </tr>
                            <tr>
                                <th>Checkout URL</th>
                                <td>
                                    @if ($url = data_get($donation->tripayTransaction->tripay_payload, 'data.checkout_url'))
                                        <a href="{{ $url }}" target="_blank">
                                            Buka Halaman Pembayaran
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </table>
                    @else
                        <p class="text-muted">
                            Data transaksi Tripay tidak tersedia.
                        </p>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection
