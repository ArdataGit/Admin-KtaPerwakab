@extends('layouts.app')

@section('title', 'Master Divisi')

@section('page-title', 'Master Divisi')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Master Divisi</h2>
        <button onclick="showCreateModal()" 
                class="px-5 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i>Tambah Divisi
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Divisi</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($divisions as $i => $d)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $divisions->firstItem() + $i }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $d->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="showEditModal({{ $d->id }}, '{{ addslashes($d->name) }}')"
                                            class="px-3 py-1.5 bg-yellow-500 text-white text-xs font-medium rounded-lg hover:bg-yellow-600 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>

                                    <button onclick="confirmDelete({{ $d->id }})"
                                            class="px-3 py-1.5 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-sitemap text-4xl mb-2 text-gray-300"></i>
                                <p>Tidak ada data divisi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $divisions->links() }}
        </div>
    </div>

    <!-- Hidden Forms for Delete -->
    @foreach ($divisions as $d)
        <form id="delete-form-{{ $d->id }}" 
              action="{{ route('division.destroy', $d->id) }}" 
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
                title: 'Tambah Divisi',
                html: `
                    <form id="createForm" action="{{ route('division.store') }}" method="POST">
                        @csrf
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Divisi <span class="text-red-500">*</span></label>
                            <input type="text" name="name" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   placeholder="Contoh: Divisi Humas, Divisi Keuangan" required>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-save mr-2"></i>Simpan',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                width: '500px',
                preConfirm: () => {
                    const name = document.querySelector('input[name="name"]').value;
                    if (!name) {
                        Swal.showValidationMessage('Nama divisi wajib diisi');
                        return false;
                    }
                    document.getElementById('createForm').submit();
                }
            });
        }

        // Edit Modal
        function showEditModal(id, name) {
            Swal.fire({
                title: 'Edit Divisi',
                html: `
                    <form id="editForm" action="{{ url('division') }}/${id}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Divisi <span class="text-red-500">*</span></label>
                            <input type="text" name="name" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   value="${name}" required>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-save mr-2"></i>Update',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6b7280',
                width: '500px',
                preConfirm: () => {
                    const name = document.querySelector('input[name="name"]').value;
                    if (!name) {
                        Swal.showValidationMessage('Nama divisi wajib diisi');
                        return false;
                    }
                    document.getElementById('editForm').submit();
                }
            });
        }

        // Delete Confirmation
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Divisi?',
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
