@extends('layouts.app')

@section('title', 'Daftar Info Duka')

@section('page-title', 'Daftar Info Duka')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Info Duka</h2>
        <button onclick="showCreateModal()" 
                class="px-5 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i>Tambah Info Duka
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Almarhum</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Wafat</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Foto</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($items as $i => $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $items->firstItem() + $i }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-medium text-gray-900">{{ $item->nama_almarhum }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $item->usia ? $item->usia . ' th' : '-' }} • {{ $item->asal ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $item->judul }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $item->tanggal_wafat->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if ($item->is_active)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Aktif</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->foto)
                                    <a href="{{ asset('storage/' . $item->foto) }}" target="_blank" class="inline-block">
                                        <img src="{{ asset('storage/' . $item->foto) }}"
                                             class="w-16 h-16 object-cover rounded-lg shadow-md hover:shadow-lg transition-shadow mx-auto"
                                             alt="Foto">
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick='showEditModal({{ $item->id }}, "{{ addslashes($item->nama_almarhum) }}", "{{ addslashes($item->judul) }}", "{{ $item->tanggal_wafat->format('Y-m-d') }}", {{ $item->usia ?? 'null' }}, "{{ addslashes($item->asal ?? '') }}", {{ $item->is_active ? 'true' : 'false' }}, "{{ addslashes($item->isi ?? '') }}", "{{ optional($item->tanggal_publish)->format('Y-m-d\TH:i') }}")'
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
                                <i class="fas fa-heart-broken text-4xl mb-2 text-gray-300"></i>
                                <p>Belum ada info duka</p>
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
              action="{{ route('info-duka.destroy', $item->id) }}" 
              method="POST" 
              style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
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

        function showCreateModal() {
            Swal.fire({
                title: 'Tambah Info Duka',
                html: `
                    <form id="createForm" action="{{ route('info-duka.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="text-left mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Almarhum</label>
                            <input type="text" name="nama_almarhum" class="w-full px-3 py-2 border rounded-lg" required>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div class="text-left">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Usia</label>
                                <input type="number" name="usia" class="w-full px-3 py-2 border rounded-lg">
                            </div>
                            <div class="text-left">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Asal</label>
                                <input type="text" name="asal" class="w-full px-3 py-2 border rounded-lg">
                            </div>
                        </div>
                        <div class="text-left mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                            <input type="text" name="judul" class="w-full px-3 py-2 border rounded-lg" required>
                        </div>
                        <div class="text-left mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Isi (Editor)</label>
                            <textarea id="isi-create" name="isi" rows="4" class="w-full px-3 py-2 border rounded-lg" required></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div class="text-left">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Wafat</label>
                                <input type="date" name="tanggal_wafat" class="w-full px-3 py-2 border rounded-lg" required>
                            </div>
                            <div class="text-left">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publish</label>
                                <input type="datetime-local" name="tanggal_publish" class="w-full px-3 py-2 border rounded-lg" required>
                            </div>
                        </div>
                        <div class="text-left mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto (1 Foto)</label>
                            <input type="file" name="foto" class="w-full px-3 py-2 border rounded-lg" accept="image/jpeg,image/jpg,image/png">
                            <p class="text-xs text-gray-500 mt-1">Format didukung: JPG, JPEG, PNG</p>
                        </div>
                        <div class="text-left mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="is_active" class="w-full px-3 py-2 border rounded-lg">
                                <option value="1" selected>Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </form>
                `,
                width: '700px',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3E9A3E',
                cancelButtonColor: '#6b7280',
                didOpen: () => {
                    // Initialize CKEditor for create modal
                    if (typeof ClassicEditor !== 'undefined') {
                        ClassicEditor
                            .create(document.querySelector('#isi-create'), {
                                toolbar: ['undo', 'redo', '|', 'heading', '|', 'bold', 'italic', '|', 'link', 'insertImage', 'insertTable', '|', 'blockQuote', 'mediaEmbed', '|', 'bulletedList', 'numberedList', '|', 'indent', 'outdent']
                            })
                            .catch(error => console.error(error));
                    }
                },
                preConfirm: () => {
                    document.getElementById('createForm').submit();
                }
            });
        }

        function showEditModal(id, nama, judul, tanggal, usia, asal, isActive, isi, tanggalPublish) {
            Swal.fire({
                title: 'Edit Info Duka',
                html: `
                    <form id="editForm" action="{{ url('info-duka') }}/${id}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="text-left mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Almarhum</label>
                            <input type="text" name="nama_almarhum" value="${nama}" class="w-full px-3 py-2 border rounded-lg" required>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div class="text-left">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Usia</label>
                                <input type="number" name="usia" value="${usia || ''}" class="w-full px-3 py-2 border rounded-lg">
                            </div>
                            <div class="text-left">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Asal</label>
                                <input type="text" name="asal" value="${asal || ''}" class="w-full px-3 py-2 border rounded-lg">
                            </div>
                        </div>
                        <div class="text-left mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                            <input type="text" name="judul" value="${judul}" class="w-full px-3 py-2 border rounded-lg" required>
                        </div>
                        <div class="text-left mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Isi (Editor)</label>
                            <textarea id="isi-edit-${id}" name="isi" rows="4" class="w-full px-3 py-2 border rounded-lg" required>${isi || ''}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div class="text-left">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Wafat</label>
                                <input type="date" name="tanggal_wafat" value="${tanggal}" class="w-full px-3 py-2 border rounded-lg" required>
                            </div>
                            <div class="text-left">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publish</label>
                                <input type="datetime-local" name="tanggal_publish" value="${tanggalPublish || ''}" class="w-full px-3 py-2 border rounded-lg" required>
                            </div>
                        </div>
                        <div class="text-left mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Baru (opsional)</label>
                            <input type="file" name="foto" class="w-full px-3 py-2 border rounded-lg" accept="image/jpeg,image/jpg,image/png">
                            <p class="text-xs text-gray-500 mt-1">Format didukung: JPG, JPEG, PNG</p>
                        </div>
                        <div class="text-left mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="is_active" class="w-full px-3 py-2 border rounded-lg">
                                <option value="1" ${isActive ? 'selected' : ''}>Aktif</option>
                                <option value="0" ${!isActive ? 'selected' : ''}>Nonaktif</option>
                            </select>
                        </div>
                    </form>
                `,
                width: '700px',
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6b7280',
                didOpen: () => {
                    // Initialize CKEditor for edit modal
                    if (typeof ClassicEditor !== 'undefined') {
                        ClassicEditor
                            .create(document.querySelector('#isi-edit-' + id), {
                                toolbar: ['undo', 'redo', '|', 'heading', '|', 'bold', 'italic', '|', 'link', 'insertImage', 'insertTable', '|', 'blockQuote', 'mediaEmbed', '|', 'bulletedList', 'numberedList', '|', 'indent', 'outdent']
                            })
                            .catch(error => console.error(error));
                    }
                },
                preConfirm: () => {
                    document.getElementById('editForm').submit();
                }
            });
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Info Duka?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endpush
