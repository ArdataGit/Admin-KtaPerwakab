@extends('layouts.app')

@section('title', 'Master User')

@section('page-title', 'Master User')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Master User</h2>
        <button onclick="showCreateModal()" class="px-5 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i>Tambah User
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Telepon</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Foto</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($users as $i => $u)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $users->firstItem() + $i }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $u->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $u->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $u->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                    {{ ucfirst($u->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $u->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($u->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($u->profile_photo_url)
                                    <img src="{{ $u->profile_photo_url }}" class="w-10 h-10 rounded-full object-cover" alt="Profile">
                                @else
                                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button onclick='showEditModal(@json($u))' class="px-3 py-1.5 bg-yellow-500 text-white text-xs font-medium rounded-lg hover:bg-yellow-600 transition-colors">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>

                                    <button onclick="confirmDelete({{ $u->id }})" class="px-3 py-1.5 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors">
                                        <i class="fas fa-trash mr-1"></i>Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Hidden Forms for Delete -->
    @foreach ($users as $u)
        <form id="delete-form-{{ $u->id }}" 
              action="{{ route('user.destroy', $u->id) }}" 
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
            title: '<strong>Tambah User</strong>',
            html: `
                <form id="createForm" action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data" class="text-left p-4">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
                            <input type="text" name="name" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="email" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                                <input type="text" name="phone"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                                <select name="gender"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                                    <option value="">-</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                                <select name="role" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                                    <option value="superadmin">Superadmin</option>
                                    <option value="admin">Admin</option>
                                    <option value="pengurus">Pengurus</option>
                                    <option value="anggota">Anggota</option>
                                    <option value="bendahara">Bendahara</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                                <select name="status" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <input type="password" name="password" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm"
                                   placeholder="Minimal 8 karakter">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                            <input type="file" name="profile_photo" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG</p>
                        </div>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#3E9A3E',
            cancelButtonColor: '#6b7280',
            width: '600px',
            customClass: {
                popup: 'rounded-xl',
                htmlContainer: 'p-0',
                confirmButton: 'px-6 py-2 rounded-lg',
                cancelButton: 'px-6 py-2 rounded-lg'
            },
            preConfirm: () => {
                const form = document.getElementById('createForm');
                form.submit();
            }
        });
    }

    // Edit Modal
    function showEditModal(user) {
        Swal.fire({
            title: '<strong>Edit User</strong>',
            html: `
                <form id="editForm" action="/user/${user.id}" method="POST" enctype="multipart/form-data" class="text-left p-4">
                    @csrf
                    @method('PUT')
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
                            <input type="text" name="name" value="${user.name}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="email" value="${user.email}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                                <input type="text" name="phone" value="${user.phone || ''}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                                <select name="gender"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                                    <option value="">-</option>
                                    <option value="L" ${user.gender == 'L' ? 'selected' : ''}>Laki-laki</option>
                                    <option value="P" ${user.gender == 'P' ? 'selected' : ''}>Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                                <select name="role" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                                    <option value="superadmin" ${user.role == 'superadmin' ? 'selected' : ''}>Superadmin</option>
                                    <option value="admin" ${user.role == 'admin' ? 'selected' : ''}>Admin</option>
                                    <option value="pengurus" ${user.role == 'pengurus' ? 'selected' : ''}>Pengurus</option>
                                    <option value="anggota" ${user.role == 'anggota' ? 'selected' : ''}>Anggota</option>
                                    <option value="bendahara" ${user.role == 'bendahara' ? 'selected' : ''}>Bendahara</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                                <select name="status" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                                    <option value="aktif" ${user.status == 'aktif' ? 'selected' : ''}>Aktif</option>
                                    <option value="nonaktif" ${user.status == 'nonaktif' ? 'selected' : ''}>Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-gray-400 text-xs">(opsional)</span></label>
                            <input type="password" name="password"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                            <input type="file" name="profile_photo" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG</p>
                        </div>

                        ${user.profile_photo_url ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Foto Saat Ini</label>
                            <img src="${user.profile_photo_url}" class="w-20 h-20 rounded-lg object-cover border border-gray-200">
                        </div>
                        ` : ''}
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6b7280',
            width: '600px',
            customClass: {
                popup: 'rounded-xl',
                htmlContainer: 'p-0',
                confirmButton: 'px-6 py-2 rounded-lg',
                cancelButton: 'px-6 py-2 rounded-lg'
            },
            preConfirm: () => {
                const form = document.getElementById('editForm');
                form.submit();
            }
        });
    }

    // Delete Confirmation
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus User?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'px-6 py-2 rounded-lg',
                cancelButton: 'px-6 py-2 rounded-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush
