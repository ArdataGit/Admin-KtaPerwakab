@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard Overview</h2>
        <button class="px-4 py-2 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2">
            <i class="fas fa-download text-sm"></i>
            Generate Report
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        
        <!-- Card 1: Jumlah Anggota -->
        <a href="{{ route('user.anggota') }}" class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-all cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-blue-500 uppercase tracking-wide mb-1">Jumlah Anggota</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($jumlahAnggota) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl text-blue-400"></i>
                </div>
            </div>
        </a>

        <!-- Card 2: Total Iuran Diterima -->
        <a href="{{ route('membership-fee.index') }}" class="bg-white rounded-xl shadow-md p-6 border-l-4 border-[#3E9A3E] hover:shadow-lg transition-all cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-[#3E9A3E] uppercase tracking-wide mb-1">Total Iuran Diterima</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalIuran, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-2xl text-[#3E9A3E]"></i>
                </div>
            </div>
        </a>

        <!-- Card 3: Jumlah Produk UMKM -->
        <a href="{{ route('umkm-product.index') }}" class="bg-white rounded-xl shadow-md p-6 border-l-4 border-cyan-500 hover:shadow-lg transition-all cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-cyan-500 uppercase tracking-wide mb-1">Jumlah Produk</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($jumlahProduk) }}</p>
                </div>
                <div class="w-12 h-12 bg-cyan-50 rounded-full flex items-center justify-center">
                    <i class="fas fa-box text-2xl text-cyan-400"></i>
                </div>
            </div>
        </a>

        <!-- Card 4: Jumlah Karya dan Bisnis -->
        <a href="{{ route('bisnis.index') }}" class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition-all cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-yellow-500 uppercase tracking-wide mb-1">Jumlah Karya & Bisnis</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($jumlahBisnis) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center">
                    <i class="fas fa-briefcase text-2xl text-yellow-400"></i>
                </div>
            </div>
        </a>
    </div>

       <!-- Tabel Anggota & Transaksi Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        
        <!-- 10 Anggota Terbaru -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-user-plus text-blue-500"></i>
                    10 Anggota Terbaru
                </h3>
                <a href="{{ route('user.anggota') }}" class="text-sm text-blue-500 hover:text-blue-600 hover:underline">
                    Lihat Semua →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tgl Bergabung</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($anggotaTerbaru as $anggota)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($anggota->profile_photo)
                                            <img src="{{ asset('storage/profile_photos/' . $anggota->profile_photo) }}" 
                                                 class="w-8 h-8 rounded-full object-cover" 
                                                 alt="Profile">
                                        @else
                                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-gray-400 text-xs"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $anggota->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $anggota->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $anggota->role == 'anggota' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($anggota->role) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $anggota->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fas fa-users text-2xl mb-2 text-gray-300"></i>
                                    <p class="text-sm">Belum ada anggota</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 10 Transaksi Iuran Terbaru -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-money-bill-wave text-[#3E9A3E]"></i>
                    10 Transaksi Iuran Terbaru
                </h3>
                <a href="{{ route('membership-fee.index') }}" class="text-sm text-[#3E9A3E] hover:text-[#2d7a2d] hover:underline">
                    Lihat Semua →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Anggota</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Nominal</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($transaksiTerbaru as $transaksi)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $transaksi->user->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $transaksi->created_at->format('d M Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="text-sm font-semibold text-gray-900">
                                        Rp {{ number_format($transaksi->amount, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ ucfirst($transaksi->type) }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($transaksi->payment_status === 'success')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                            Berhasil
                                        </span>
                                    @elseif($transaksi->payment_status === 'pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">
                                            Pending
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">
                                            Gagal
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fas fa-receipt text-2xl mb-2 text-gray-300"></i>
                                    <p class="text-sm">Belum ada transaksi</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Quick Access Menu -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Menu Master Data -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-database text-[#3E9A3E]"></i>
                    Master Data
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('user.anggota') }}" class="flex items-center gap-3 p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all group">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">User Anggota</div>
                            <div class="text-xs text-gray-500">Kelola anggota</div>
                        </div>
                    </a>

                    <a href="{{ route('umkm.index') }}" class="flex items-center gap-3 p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-all group">
                        <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-store text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">UMKM</div>
                            <div class="text-xs text-gray-500">Kelola UMKM</div>
                        </div>
                    </a>

                    <a href="{{ route('umkm-product.index') }}" class="flex items-center gap-3 p-4 bg-cyan-50 hover:bg-cyan-100 rounded-lg transition-all group">
                        <div class="w-10 h-10 bg-cyan-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-box text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Produk UMKM</div>
                            <div class="text-xs text-gray-500">Kelola produk</div>
                        </div>
                    </a>

                    <a href="{{ route('bisnis.index') }}" class="flex items-center gap-3 p-4 bg-amber-50 hover:bg-amber-100 rounded-lg transition-all group">
                        <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-briefcase text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Karya & Bisnis</div>
                            <div class="text-xs text-gray-500">Kelola karya</div>
                        </div>
                    </a>

                    <a href="{{ route('master.donation-campaign.index') }}" class="flex items-center gap-3 p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-all group">
                        <div class="w-10 h-10 bg-[#3E9A3E] rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-hand-holding-heart text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Donasi</div>
                            <div class="text-xs text-gray-500">Campaign donasi</div>
                        </div>
                    </a>

                    <a href="{{ route('point-kategoris.index') }}" class="flex items-center gap-3 p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-all group">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-star text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Point Kategori</div>
                            <div class="text-xs text-gray-500">Kelola point</div>
                        </div>
                    </a>

                    <a href="{{ route('info-duka.index') }}" class="flex items-center gap-3 p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition-all group">
                        <div class="w-10 h-10 bg-gray-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Info Duka</div>
                            <div class="text-xs text-gray-500">Informasi duka</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Menu Transaksi & Laporan -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-chart-line text-[#3E9A3E]"></i>
                    Transaksi & Laporan
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('membership-fee.index') }}" class="flex items-center gap-3 p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-all group">
                        <div class="w-10 h-10 bg-[#3E9A3E] rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-money-bill-wave text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Iuran Anggota</div>
                            <div class="text-xs text-gray-500">Kelola iuran</div>
                        </div>
                    </a>

                    <a href="{{ route('tukar-point.index') }}" class="flex items-center gap-3 p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-all group">
                        <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-exchange-alt text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Tukar Point</div>
                            <div class="text-xs text-gray-500">Penukaran point</div>
                        </div>
                    </a>

                    <a href="{{ route('penukaran-poin.index') }}" class="flex items-center gap-3 p-4 bg-pink-50 hover:bg-pink-100 rounded-lg transition-all group">
                        <div class="w-10 h-10 bg-pink-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-gift text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Hadiah Point</div>
                            <div class="text-xs text-gray-500">Katalog hadiah</div>
                        </div>
                    </a>

                    <a href="{{ route('news.index') }}" class="flex items-center gap-3 p-4 bg-red-50 hover:bg-red-100 rounded-lg transition-all group">
                        <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-newspaper text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Berita</div>
                            <div class="text-xs text-gray-500">Kelola berita</div>
                        </div>
                    </a>

                    <a href="{{ route('publikasi.index') }}" class="flex items-center gap-3 p-4 bg-teal-50 hover:bg-teal-100 rounded-lg transition-all group">
                        <div class="w-10 h-10 bg-teal-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-book text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Publikasi</div>
                            <div class="text-xs text-gray-500">Kelola publikasi</div>
                        </div>
                    </a>

                    <a href="{{ route('struktur-organisasi.index') }}" class="flex items-center gap-3 p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition-all group">
                        <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-sitemap text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800">Struktur Organisasi</div>
                            <div class="text-xs text-gray-500">Kelola struktur</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
