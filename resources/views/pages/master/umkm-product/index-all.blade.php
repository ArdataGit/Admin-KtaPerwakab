@extends('layouts.app')

@section('title', 'Semua Produk UMKM')

@section('page-title', 'Semua Produk UMKM')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Semua Produk UMKM</h2>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">UMKM</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Produk</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Foto</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($products as $i => $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $products->firstItem() + $i }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $product->umkm->user->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $product->umkm->category ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $product->product_name }}</div>
                                @if($product->description)
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ \Illuminate\Support\Str::limit($product->description, 50) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if ($product->price)
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($product->status === 'approved')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Disetujui</span>
                                @elseif($product->status === 'pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Pending</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($product->photos->count())
                                    <a href="{{ asset('storage/' . $product->photos->first()->file_path) }}" target="_blank" class="inline-block">
                                        <img src="{{ asset('storage/' . $product->photos->first()->file_path) }}"
                                             class="w-16 h-16 object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow mx-auto"
                                             alt="Product photo">
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2 flex-wrap">
                                    {{-- Tombol Lihat Detail UMKM --}}
                                    <a href="{{ route('umkm.products.index', $product->umkm_id) }}"
                                       class="px-3 py-1.5 bg-blue-500 text-white text-xs font-medium rounded-lg hover:bg-blue-600 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>Lihat UMKM
                                    </a>

                                    {{-- Tombol Approve/Reject (hanya untuk pending) --}}
                                    @if($product->status === 'pending')
                                        <form action="{{ route('umkm.products.approve', [$product->umkm_id, $product->id]) }}" 
                                              method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Setujui produk ini?')"
                                                    class="px-3 py-1.5 bg-green-500 text-white text-xs font-medium rounded-lg hover:bg-green-600 transition-colors">
                                                <i class="fas fa-check mr-1"></i>Setujui
                                            </button>
                                        </form>

                                        <form action="{{ route('umkm.products.reject', [$product->umkm_id, $product->id]) }}" 
                                              method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Tolak produk ini?')"
                                                    class="px-3 py-1.5 bg-orange-500 text-white text-xs font-medium rounded-lg hover:bg-orange-600 transition-colors">
                                                <i class="fas fa-times mr-1"></i>Tolak
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('umkm.products.edit', [$product->umkm_id, $product->id]) }}"
                                       class="px-3 py-1.5 bg-yellow-500 text-white text-xs font-medium rounded-lg hover:bg-yellow-600 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('umkm.products.destroy', [$product->umkm_id, $product->id]) }}" 
                                          method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Hapus produk ini?')"
                                                class="px-3 py-1.5 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-2 text-gray-300"></i>
                                <p>Belum ada produk</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $products->links() }}
        </div>
    </div>

@endsection
