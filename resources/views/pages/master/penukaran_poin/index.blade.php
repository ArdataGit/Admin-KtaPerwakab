@extends('layouts.app')

@section('title', 'Master Produk Penukaran Poin')

@section('page-title', 'Master Produk Penukaran Poin')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Master Produk Penukaran Poin</h2>
        <button onclick="showCreateModal()" 
                class="px-5 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i>Tambah Produk
        </button>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('penukaran-poin.index') }}" class="mb-6">
        <div class="flex gap-2">
            <input type="text" name="search" 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" 
                   placeholder="Cari berdasarkan nama produk..."
                   value="{{ request('search') }}">
            <button type="submit" 
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            @if(request('search'))
                <a href="{{ route('penukaran-poin.index') }}" 
                   class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            @endif
        </div>
    </form>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah Poin</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($items as $i => $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $items->firstItem() + $i }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $item->produk }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $item->keterangan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->image)
                                    <a href="{{ asset('storage/' . $item->image) }}" target="_blank" class="inline-block">
                                        <img src="{{ asset('storage/' . $item->image) }}"
                                             class="w-16 h-16 object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow mx-auto"
                                             alt="Product image">
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">
                                    {{ $item->jumlah_poin }} poin
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="showEditModal({{ $item->id }}, '{{ addslashes($item->produk) }}', '{{ addslashes($item->keterangan ?? '') }}', {{ $item->jumlah_poin }}, '{{ $item->image }}')"
                                            class="px-3 py-1.5 bg-yellow-500 text-white text-xs font-medium rounded-lg hover:bg-yellow-600 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>

                                    <button onclick="confirmDelete({{ $item->id }})"
                                            class="px-3 py-1.5 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-gift text-4xl mb-2 text-gray-300"></i>
                                <p>Tidak ada data produk penukaran poin</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $items->links() }}
        </div>
    </div>

    <!-- Hidden Forms for Delete -->
    @foreach ($items as $item)
        <form id="delete-form-{{ $item->id }}" 
              action="{{ route('penukaran-poin.destroy', $item->id) }}" 
              method="POST" 
              style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Show success/error messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                timer: 2000,
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

        // Create Modal
        function showCreateModal() {
            Swal.fire({
                title: 'Tambah Produk Penukaran Poin',
                html: `
                    <form id="createForm" action="{{ route('penukaran-poin.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" name="produk" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   placeholder="Contoh: Rinso 1kg" required>
                        </div>
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                            <textarea name="keterangan" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                      rows="3"
                                      placeholder="Berlaku s/d, syarat, dll"></textarea>
                        </div>
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Poin <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah_poin" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   placeholder="Contoh: 100" min="1" required>
                        </div>
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                            <input type="file" name="image" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                                   accept="image/jpeg,image/jpg,image/png,image/webp">
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>Format: JPG, JPEG, PNG, WEBP (Max 2MB)
                            </p>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-save mr-2"></i>Simpan',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                width: '600px',
                preConfirm: () => {
                    document.getElementById('createForm').submit();
                }
            });
        }

        // Edit Modal
        function showEditModal(id, produk, keterangan, jumlahPoin, currentImage) {
            const imagePreview = currentImage ? 
                `<div class="mt-2 p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-600 mb-2">Gambar saat ini:</p>
                    <img src="{{ asset('storage') }}/${currentImage}" class="w-24 h-24 object-cover rounded-lg shadow-md mx-auto" alt="Current image">
                    <p class="text-xs text-gray-500 mt-2">Upload gambar baru untuk mengganti</p>
                </div>` : '';

            Swal.fire({
                title: 'Edit Produk Penukaran Poin',
                html: `
                    <form id="editForm" action="{{ url('/penukaran-poin') }}/${id}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" name="produk" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   value="${produk}" required>
                        </div>
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                            <textarea name="keterangan" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                      rows="3">${keterangan}</textarea>
                        </div>
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Poin <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah_poin" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   value="${jumlahPoin}" min="1" required>
                        </div>
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                            <input type="file" name="image" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                                   accept="image/jpeg,image/jpg,image/png,image/webp">
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>Format: JPG, JPEG, PNG, WEBP (Max 2MB)
                            </p>
                            ${imagePreview}
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-save mr-2"></i>Update',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6b7280',
                width: '600px',
                preConfirm: () => {
                    document.getElementById('editForm').submit();
                }
            });
        }

        // Delete Confirmation
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Produk?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endpush
