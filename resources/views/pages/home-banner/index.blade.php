@extends('layouts.app')

@section('title', 'Banner Home')

@section('page-title', 'Banner Home')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Banner Home</h2>
        <button onclick="showCreateModal()" 
                class="px-5 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i>Tambah Banner
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subjudul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Posisi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gambar</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($banners as $i => $banner)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $banners->firstItem() + $i }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $banner->title ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $banner->subtitle ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $banner->position }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if ($banner->is_active)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Aktif</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <img src="{{ $banner->image ? asset('storage/'.$banner->image) : asset('images/no-image.png') }}"
                                     class="w-20 h-12 rounded-lg object-cover border border-gray-200 shadow-sm" alt="Banner">
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick='showEditModal(@json($banner))' 
                                            class="px-3 py-1.5 bg-yellow-500 text-white text-xs font-medium rounded-lg hover:bg-yellow-600 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>

                                    <button onclick="confirmDelete({{ $banner->id }})" 
                                            class="px-3 py-1.5 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-image text-4xl mb-2 text-gray-300"></i>
                                <p>Tidak ada data banner</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $banners->links() }}
        </div>
    </div>

    <!-- Hidden Forms for Delete -->
    @foreach ($banners as $banner)
        <form id="delete-form-{{ $banner->id }}" 
              action="{{ route('home-banner.destroy', $banner->id) }}" 
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
            title: 'Tambah Banner',
            html: `
                <form id="createForm" action="{{ route('home-banner.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 text-left p-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                            <input type="text" name="title" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm" 
                                   placeholder="Judul banner">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subjudul</label>
                            <input type="text" name="subtitle" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm" 
                                   placeholder="Subjudul banner">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="description" rows="3"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm" 
                                   placeholder="Deskripsi banner"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Posisi</label>
                                <input type="number" name="position" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm" 
                                       value="0" min="0" step="1">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="is_active" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Periode Tampil (Opsional)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="datetime-local" name="start_at" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                                <input type="datetime-local" name="end_at" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Banner <span class="text-red-500">*</span></label>
                            <input type="file" name="image" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" 
                                   accept="image/*" required>
                            <small class="text-gray-500 text-xs">Rekomendasi ukuran: 1200x400 px</small>
                        </div>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save mr-2"></i>Simpan',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            width: '700px',
            customClass: {
                popup: 'swal-wide',
                htmlContainer: 'swal-html-container'
            },
            preConfirm: () => {
                const form = document.getElementById('createForm');
                const image = form.querySelector('input[name="image"]').files[0];
                
                if (!image) {
                    Swal.showValidationMessage('Gambar banner wajib diisi');
                    return false;
                }
                
                form.submit();
            }
        });
    }

    // Edit Modal
    function showEditModal(banner) {
        const startAt = banner.start_at ? new Date(banner.start_at).toISOString().slice(0, 16) : '';
        const endAt = banner.end_at ? new Date(banner.end_at).toISOString().slice(0, 16) : '';
        
        Swal.fire({
            title: 'Edit Banner',
            html: `
                <form id="editForm" action="/home-banner/${banner.id}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 text-left p-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                            <input type="text" name="title" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm" 
                                   value="${banner.title || ''}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subjudul</label>
                            <input type="text" name="subtitle" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm" 
                                   value="${banner.subtitle || ''}">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="description" rows="3"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">${banner.description || ''}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Posisi</label>
                                <input type="number" name="position" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm" 
                                       value="${banner.position}" min="0" step="1">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="is_active" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                                    <option value="1" ${banner.is_active ? 'selected' : ''}>Aktif</option>
                                    <option value="0" ${!banner.is_active ? 'selected' : ''}>Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Periode Tampil</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="datetime-local" name="start_at" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                                       value="${startAt}">
                                <input type="datetime-local" name="end_at" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                                       value="${endAt}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Banner</label>
                            <input type="file" name="image" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" 
                                   accept="image/*">
                            <small class="text-gray-500 text-xs">Kosongkan jika tidak ingin mengubah gambar</small>
                        </div>

                        ${banner.image ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                            <img src="{{ asset('storage/') }}/${banner.image}" class="w-32 h-20 rounded-lg object-cover border-2 border-gray-200 shadow-sm">
                        </div>
                        ` : ''}
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save mr-2"></i>Update',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6b7280',
            width: '700px',
            customClass: {
                popup: 'swal-wide',
                htmlContainer: 'swal-html-container'
            },
            preConfirm: () => {
                document.getElementById('editForm').submit();
            }
        });
    }

    // Delete Confirmation
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Banner?',
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

<style>
    .swal-wide {
        max-height: 90vh;
        overflow-y: auto;
    }
    .swal-html-container {
        max-height: 70vh;
        overflow-y: auto;
        padding: 0 !important;
        margin: 0 !important;
    }
    .swal2-html-container::-webkit-scrollbar {
        width: 8px;
    }
    .swal2-html-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .swal2-html-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    .swal2-html-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush
