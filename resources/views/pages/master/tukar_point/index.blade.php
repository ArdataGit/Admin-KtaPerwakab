@extends('layouts.app')

@section('title', 'Submenu Tukar Point')

@section('page-title', 'Submenu Tukar Point')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Submenu Tukar Point</h2>
        <button onclick="showCreateModal()" 
                class="px-5 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i>Add Point
        </button>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('tukar-point.index') }}" class="mb-6">
        <div class="flex gap-2">
            <input type="text" name="search" 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" 
                   placeholder="Cari berdasarkan nama user..."
                   value="{{ request('search') }}">
            <button type="submit" 
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-search mr-2"></i>Search
            </button>
            @if(request('search'))
                <a href="{{ route('tukar-point.index') }}" 
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk Penukaran</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Point</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($items as $i => $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $items->firstItem() + $i }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $item->user->name ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $item->masterPenukaran->produk ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">
                                    {{ $item->point }} poin
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $item->keterangan }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="showEditModal({{ $item->id }}, {{ $item->user_id }}, '{{ addslashes($item->user->name ?? '') }}', {{ $item->master_penukaran_poin_id }}, '{{ $item->tanggal }}')"
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
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-exchange-alt text-4xl mb-2 text-gray-300"></i>
                                <p>Tidak ada data tukar point</p>
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
              action="{{ route('tukar-point.destroy', $item->id) }}" 
              method="POST" 
              style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container {
            width: 100% !important;
        }
        .select2-container .select2-selection--single {
            height: 42px;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
        }
        .select2-selection__rendered {
            line-height: 26px;
            padding-left: 0 !important;
        }
        .select2-selection__arrow {
            height: 42px;
            right: 8px;
        }
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
        .select2-dropdown {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                title: 'Add Tukar Point',
                html: `
                    <form id="createForm" action="{{ route('tukar-point.store') }}" method="POST">
                        @csrf
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">User <span class="text-red-500">*</span></label>
                            <select name="user_id" id="user-select-create" class="w-full" required>
                                <option value="">-- Pilih User --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} ({{ $user->point }} poin)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Produk Penukaran <span class="text-red-500">*</span></label>
                            <select name="master_penukaran_poin_id" id="produk-select-create" class="w-full" required>
                                <option value="">-- Pilih Produk --</option>
                                @foreach($produkPenukaran as $produk)
                                    <option value="{{ $produk->id }}">
                                        {{ $produk->produk }} ({{ $produk->jumlah_poin }} poin)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   required>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-save mr-2"></i>Simpan',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                width: '600px',
                didOpen: () => {
                    $('#user-select-create').select2({
                        placeholder: "Cari dan pilih user...",
                        allowClear: true,
                        dropdownParent: $('.swal2-container')
                    });
                    $('#produk-select-create').select2({
                        placeholder: "Cari dan pilih produk...",
                        allowClear: true,
                        dropdownParent: $('.swal2-container')
                    });
                },
                preConfirm: () => {
                    const userId = $('#user-select-create').val();
                    const produkId = $('#produk-select-create').val();
                    const tanggal = document.querySelector('input[name="tanggal"]').value;
                    
                    if (!userId || !produkId || !tanggal) {
                        Swal.showValidationMessage('Semua field wajib diisi');
                        return false;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('createForm').submit();
                }
            });
        }

        // Edit Modal
        function showEditModal(id, userId, userName, produkId, tanggal) {
            Swal.fire({
                title: 'Edit Tukar Point',
                html: `
                    <form id="editForm" action="{{ url('/point/tukar') }}/${id}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                            <input type="text" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" 
                                   value="${userName}" 
                                   disabled>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>User tidak dapat diubah
                            </p>
                        </div>
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Produk Penukaran <span class="text-red-500">*</span></label>
                            <select name="master_penukaran_poin_id" id="produk-select-edit" class="w-full" required>
                                @foreach($produkPenukaran as $produk)
                                    <option value="{{ $produk->id }}" ${produkId == {{ $produk->id }} ? 'selected' : ''}>
                                        {{ $produk->produk }} ({{ $produk->jumlah_poin }} poin)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   value="${tanggal}"
                                   required>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-save mr-2"></i>Update',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6b7280',
                width: '600px',
                didOpen: () => {
                    $('#produk-select-edit').select2({
                        placeholder: "Cari dan pilih produk...",
                        allowClear: true,
                        dropdownParent: $('.swal2-container')
                    });
                },
                preConfirm: () => {
                    document.getElementById('editForm').submit();
                }
            });
        }

        // Delete Confirmation
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Data Tukar Point?',
                text: "Poin akan dikembalikan ke user!",
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
