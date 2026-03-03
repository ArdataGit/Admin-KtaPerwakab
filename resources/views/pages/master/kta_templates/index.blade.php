@extends('layouts.app')

@section('title', 'Master Template KTA')

@section('page-title', 'Master Template KTA')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Master Template KTA</h2>
        <button onclick="showCreateModal()" 
                class="px-5 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all">
            <i class="fas fa-plus mr-2"></i>Tambah Template
        </button>
    </div>

    <!-- Search -->
    <form method="GET" action="{{ route('kta-templates.index') }}" class="mb-6">
        <div class="flex gap-2">
            <input type="text" name="search" 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                   placeholder="Cari nama template..."
                   value="{{ request('search') }}">
            <button type="submit" 
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-700">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
        </div>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-xs text-gray-600 uppercase">#</th>
                        <th class="px-6 py-4 text-xs text-gray-600 uppercase">Nama Template</th>
                        <th class="px-6 py-4 text-xs text-gray-600 uppercase text-center">Depan</th>
                        <th class="px-6 py-4 text-xs text-gray-600 uppercase text-center">Belakang</th>
                        <th class="px-6 py-4 text-xs text-gray-600 uppercase text-center">Status</th>
                        <th class="px-6 py-4 text-xs text-gray-600 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($templates as $i => $template)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm">
                                {{ $templates->firstItem() + $i }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                {{ $template->name }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <img src="{{ asset('storage/'.$template->front_image) }}"
                                     class="w-24 h-14 object-cover rounded shadow mx-auto">
                            </td>
                            <td class="px-6 py-4 text-center">
                                <img src="{{ asset('storage/'.$template->back_image) }}"
                                     class="w-24 h-14 object-cover rounded shadow mx-auto">
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($template->is_active)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button onclick="showEditModal({{ $template->id }}, '{{ addslashes($template->name) }}')"
                                            class="px-3 py-1.5 bg-yellow-500 text-white text-xs rounded-lg">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button onclick="confirmDelete({{ $template->id }})"
                                            class="px-3 py-1.5 bg-red-500 text-white text-xs rounded-lg">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                  
                                  	@if(!$template->is_active)
                                    <form action="{{ route('kta-templates.activate', $template->id) }}" 
                                          method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-green-500 text-white text-xs rounded-lg">
                                            Aktifkan
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-500">
                                Tidak ada template KTA
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t">
            {{ $templates->links() }}
        </div>
    </div>

    <!-- Delete Forms -->
    @foreach ($templates as $template)
        <form id="delete-form-{{ $template->id }}"
              action="{{ route('kta-templates.destroy', $template->id) }}"
              method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

function showCreateModal() {
    Swal.fire({
        title: 'Tambah Template KTA',
        html: `
            <form id="createForm" action="{{ route('kta-templates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="text-left mb-4">
                    <label>Nama Template</label>
                    <input type="text" name="name" class="swal2-input" required>
                </div>
                <div class="text-left mb-4">
                    <label>Gambar Depan</label>
                    <input type="file" name="front_image" class="swal2-file" required>
                </div>
                <div class="text-left mb-4">
                    <label>Gambar Belakang</label>
                    <input type="file" name="back_image" class="swal2-file" required>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        preConfirm: () => {
            document.getElementById('createForm').submit();
        }
    });
}

function showEditModal(id, name) {
    Swal.fire({
        title: 'Edit Template',
        html: `
            <form id="editForm" action="/kta-templates/${id}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="text" name="name" class="swal2-input" value="${name}" required>
                <input type="file" name="front_image" class="swal2-file">
                <input type="file" name="back_image" class="swal2-file">
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Update',
        preConfirm: () => {
            document.getElementById('editForm').submit();
        }
    });
}

function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Template?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}

</script>
@endpush