@extends('layouts.app')

@section('title', 'Iuran Anggota')

@section('page-title', 'Iuran Anggota')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Iuran Anggota</h2>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Anggota</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                           placeholder="Nama atau Email">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="relative">
                    <i class="fas fa-filter absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <select name="status"
                            class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20 appearance-none bg-white">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                <div class="relative">
                    <i class="fas fa-calendar-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <select name="type"
                            class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20 appearance-none bg-white">
                        <option value="">Semua Jenis</option>
                        <option value="bulanan" {{ request('type') === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="tahunan" {{ request('type') === 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="w-full py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Anggota</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nominal</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Bukti</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($fees as $i => $fee)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $fees->firstItem() + $i }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $fee->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $fee->user->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium capitalize">
                                    {{ $fee->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                Rp{{ number_format($fee->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($fee->proof_image)
                                    <a href="{{ asset('storage/' . $fee->proof_image) }}" target="_blank" class="inline-block">
                                        <img src="{{ asset('storage/' . $fee->proof_image) }}"
                                             class="w-16 h-16 object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow mx-auto"
                                             alt="Bukti Bayar">
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($fee->payment_status === 'success')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                        Success
                                    </span>
                                @elseif ($fee->payment_status === 'failed')
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">
                                        Failed
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $fee->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($fee->payment_status === 'pending')
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('membership-fee.validate', $fee->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="payment_status" value="success">
                                            <button type="submit"
                                                    onclick="return confirm('Setujui pembayaran ini?')"
                                                    class="px-3 py-1.5 bg-[#3E9A3E] text-white text-xs font-medium rounded-lg hover:bg-[#2d7a2d] transition-colors">
                                                <i class="fas fa-check mr-1"></i>Approve
                                            </button>
                                        </form>

                                        <form action="{{ route('membership-fee.validate', $fee->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="payment_status" value="failed">
                                            <button type="submit"
                                                    onclick="return confirm('Tolak pembayaran ini?')"
                                                    class="px-3 py-1.5 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors">
                                                <i class="fas fa-times mr-1"></i>Reject
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                                <p>Belum ada data iuran</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $fees->links() }}
        </div>
    </div>

@endsection