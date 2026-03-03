@extends('layouts.app')

@section('title', 'Master Point Kategori')

@section('page-title', 'Master Point Kategori')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Master Point Kategori</h2>
        <button onclick="showCreateModal()" 
                class="px-5 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i>Tambah Kategori
        </button>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('point-kategoris.index') }}" class="mb-6">
        <div class="flex gap-2">
            <input type="text" name="search" 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" 
                   placeholder="Cari berdasarkan nama kategori..."
                   value="{{ request('search') }}">
            <button type="submit" 
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            @if(request('search'))
                <a href="{{ route('point-kategoris.index') }}" 
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Point</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pointKategoris as $i => $kategori)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $pointKategoris->firstItem() + $i }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $kategori->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                    {{ $kategori->point }} poin
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2 flex-wrap">
                                    <button onclick="showAddPointModal({{ $kategori->id }}, '{{ addslashes($kategori->name) }}', {{ $kategori->point }})"
                                            class="px-3 py-1.5 bg-blue-500 text-white text-xs font-medium rounded-lg hover:bg-green-600 transition-colors">
                                        <i class="fas fa-user-plus mr-1"></i>Tambah Point User
                                    </button>

                                    <button onclick="showEditModal({{ $kategori->id }}, '{{ addslashes($kategori->name) }}', {{ $kategori->point }})"
                                            class="px-3 py-1.5 bg-yellow-500 text-white text-xs font-medium rounded-lg hover:bg-yellow-600 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>

                                    <button onclick="confirmDelete({{ $kategori->id }})"
                                            class="px-3 py-1.5 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-award text-4xl mb-2 text-gray-300"></i>
                                <p>Tidak ada data kategori point</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pointKategoris->links() }}
        </div>
    </div>

    <!-- Hidden Forms for Delete -->
    @foreach ($pointKategoris as $kategori)
        <form id="delete-form-{{ $kategori->id }}" 
              action="{{ route('point-kategoris.destroy', $kategori->id) }}" 
              method="POST" 
              style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

@endsection

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.25rem;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
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
                title: 'Tambah Kategori Point',
                html: `
                    <form id="createForm" action="{{ route('point-kategoris.store') }}" method="POST">
                        @csrf
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                            <input type="text" name="name" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   placeholder="Contoh: Hadir Rapat" required>
                        </div>
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Point</label>
                            <input type="number" name="point" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   placeholder="Contoh: 10" min="0" required>
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
                    document.getElementById('createForm').submit();
                }
            });
        }

        // Edit Modal
        function showEditModal(id, name, point) {
            Swal.fire({
                title: 'Edit Kategori Point',
                html: `
                    <form id="editForm" action="{{ url('/point-kategoris') }}/${id}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                            <input type="text" name="name" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   value="${name}" required>
                        </div>
                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Point</label>
                            <input type="number" name="point" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                                   value="${point}" min="0" required>
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
                    document.getElementById('editForm').submit();
                }
            });
        }

        // Delete Confirmation
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Kategori?',
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

        // Add Point to Users Modal
        function showAddPointModal(kategoriId, kategoriName, kategoriPoint) {
            Swal.fire({
                title: 'Tambah Point ke User',
                html: `
                    <form id="addPointForm" action="{{ route('points.add-by-category') }}" method="POST">
                        @csrf
                        <input type="hidden" name="point_kategori_id" value="${kategoriId}">
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4 text-left">
                            <p class="text-sm"><strong>Kategori:</strong> ${kategoriName}</p>
                            <p class="text-sm"><strong>Point per User:</strong> ${kategoriPoint} poin</p>
                        </div>

                        <div class="text-left mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih User</label>
                            <select name="users[]" id="user-select-modal" class="w-full" multiple required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} ({{ $user->email }}) - Point: {{ $user->point }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>Ketik untuk mencari user, atau pilih multiple dengan CTRL/CMD
                            </p>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-check mr-2"></i>Tambahkan Point',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                width: '600px',
                didOpen: () => {
                    // Initialize Select2 after modal opens
                    $('#user-select-modal').select2({
                        placeholder: 'Pilih atau cari user',
                        allowClear: true,
                        width: '100%',
                        dropdownParent: $('.swal2-container')
                    });
                },
                preConfirm: () => {
                    const selectedUsers = $('#user-select-modal').val();
                    if (!selectedUsers || selectedUsers.length === 0) {
                        Swal.showValidationMessage('Pilih minimal 1 user');
                        return false;
                    }
                    // Return selected users and form data
                    return {
                        users: selectedUsers,
                        kategoriId: kategoriId
                    };
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const selectedCount = result.value.users.length;
                    
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: `Tambahkan point ke ${selectedCount} user terpilih?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Tambahkan!',
                        cancelButtonText: 'Batal'
                    }).then((confirmResult) => {
                        if (confirmResult.isConfirmed) {
                            // Create and submit form programmatically
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route('points.add-by-category') }}';
                            
                            // Add CSRF token
                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';
                            form.appendChild(csrfInput);
                            
                            // Add kategori ID
                            const kategoriInput = document.createElement('input');
                            kategoriInput.type = 'hidden';
                            kategoriInput.name = 'point_kategori_id';
                            kategoriInput.value = result.value.kategoriId;
                            form.appendChild(kategoriInput);
                            
                            // Add selected users
                            result.value.users.forEach(userId => {
                                const userInput = document.createElement('input');
                                userInput.type = 'hidden';
                                userInput.name = 'users[]';
                                userInput.value = userId;
                                form.appendChild(userInput);
                            });
                            
                            // Append to body and submit
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }
            });
        }
    </script>
@endpush
