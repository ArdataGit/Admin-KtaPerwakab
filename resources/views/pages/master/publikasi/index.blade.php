@extends('layouts.app')

@section('title', 'Daftar Publikasi')

@section('page-title', 'Daftar Publikasi')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Publikasi</h2>
        <a href="{{ route('publikasi.create') }}"
           class="px-5 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i>Tambah Publikasi
        </a>
    </div>

    <!-- Search Section -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('publikasi.index') }}" class="flex items-center gap-3">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20"
                       placeholder="Cari judul atau pembuat...">
            </div>
            <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            @if(request('search'))
                <a href="{{ route('publikasi.index') }}"
                   class="px-5 py-2.5 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition-all">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Search Result Info -->
    @if(request('search'))
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                <p class="text-blue-700">
                    Hasil pencarian untuk: <strong>{{ request('search') }}</strong> 
                    ({{ $publikasi->total() }} data ditemukan)
                </p>
            </div>
        </div>
    @endif

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dibuat Oleh</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($publikasi as $i => $p)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $publikasi->firstItem() + $i }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('publikasi.show', $p->id) }}" 
                                   class="font-medium text-[#3E9A3E] hover:text-[#2d7a2d] hover:underline">
                                    {{ $p->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $p->creator }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $p->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('publikasi.show', $p->id) }}"
                                       class="px-3 py-1.5 bg-blue-500 text-white text-xs font-medium rounded-lg hover:bg-blue-600 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </a>
                                    <a href="{{ route('publikasi.edit', $p->id) }}"
                                       class="px-3 py-1.5 bg-yellow-500 text-white text-xs font-medium rounded-lg hover:bg-yellow-600 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <form method="POST" action="{{ route('publikasi.destroy', $p->id) }}" class="inline-block"
                                          onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-folder-open text-4xl mb-2 text-gray-300"></i>
                                <p>Belum ada publikasi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $publikasi->links() }}
        </div>
    </div>

@endsection
