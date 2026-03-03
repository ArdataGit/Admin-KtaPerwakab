@extends('layouts.app')

@section('title', 'Master User Anggota')

@section('page-title', 'Master User Anggota')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Master User (Anggota)</h2>
        <button onclick="showCreateModal()" class="px-4 py-2 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2">
            <i class="fas fa-plus text-sm"></i>
            Tambah User
        </button>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-xl shadow-md p-4 mb-4">
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20" 
                   placeholder="Cari nama, email, atau telepon...">
            <button type="submit" class="px-6 py-2 bg-[#3E9A3E] text-white rounded-lg hover:bg-[#2d7a2d] transition-all">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
            <a href="{{ route('user.anggota') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all">
                Reset
            </a>
        </form>
        @if(request('search'))
            <p class="text-sm text-gray-500 mt-2">Hasil pencarian untuk: "{{ request('search') }}"</p>
        @endif
    </div>

    <!-- Advanced Filter Toggle -->
    <div class="mb-4">
        <button onclick="toggleFilter()" id="filterToggle" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all flex items-center gap-2">
            <i class="fas fa-filter"></i>
            Filter Lanjutan
            <i class="fas fa-chevron-down" id="filterIcon"></i>
        </button>
    </div>

    <!-- Advanced Filter Form -->
    <div id="advancedFilter" class="bg-white rounded-xl shadow-md p-4 mb-4 {{ request()->hasAny(['usia_min', 'usia_max', 'city', 'status', 'tahun_join', 'role']) ? '' : 'hidden' }}">
        <form method="GET">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20">
                        <option value="">Semua</option>
                        <option value="anggota" {{ request('role') == 'anggota' ? 'selected' : '' }}>Anggota</option>
                        <option value="publik" {{ request('role') == 'publik' ? 'selected' : '' }}>Publik</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Usia</label>
                    <div class="flex gap-2">
                        <input type="number" name="usia_min" value="{{ request('usia_min') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20" 
                               placeholder="Min">
                        <input type="number" name="usia_max" value="{{ request('usia_max') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20" 
                               placeholder="Max">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kota</label>
                    <input type="text" name="city" value="{{ request('city') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20" 
                           placeholder="Domisili">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20">
                        <option value="">Semua</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Join</label>
                    <input type="number" name="tahun_join" value="{{ request('tahun_join') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#3E9A3E] focus:ring-2 focus:ring-[#3E9A3E]/20">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-[#3E9A3E] text-white rounded-lg hover:bg-[#2d7a2d] transition-all">
                        Filter
                    </button>
                    <a href="{{ route('user.anggota') }}@if(request('search'))?search={{ request('search') }}@endif" 
                       class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all text-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>


    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <table class="w-full text-sm min-w-max">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        @php
                            $currentSort = request('sort', 'desc');
                            $newSort = $currentSort === 'asc' ? 'desc' : 'asc';
                        @endphp

                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => $newSort]) }}"
                               class="flex items-center gap-1 hover:text-[#3E9A3E] transition">
                                No.
                                @if($currentSort === 'asc')
                                    <i class="fas fa-arrow-up text-xs"></i>
                                @else
                                    <i class="fas fa-arrow-down text-xs"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                            Role / Tipe
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">No KTA</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Username</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Telepon</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Gender</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tgl Lahir</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Usia</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Point</th>
                      <!-- WILAYAH -->
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kota</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kecamatan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kelurahan</th>

                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Pekerjaan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Anggota Keluarga</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tgl Join</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Foto</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Expired</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($users as $i => $u)
                        <tr class="hover:bg-gray-50 transition-colors">
                            @php
                                $sort = request('sort', 'desc');
                                $total = $users->total();
                                $perPage = $users->perPage();
                                $currentPage = $users->currentPage();
                            @endphp

                            <td class="px-4 py-3 text-gray-700">
                                @if($sort === 'desc')
                                    {{ $total - (($currentPage - 1) * $perPage + $i) }}
                                @else
                                    {{ ($currentPage - 1) * $perPage + $i + 1 }}
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-1">

                                    <!-- ROLE BADGE -->
                                    <span class="px-2 py-1 rounded-full text-xs font-medium w-fit
                                        {{ $u->role == 'anggota' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($u->role) }}
                                    </span>

                                    <!-- MEMBER TYPE BADGE (KHUSUS ANGOTA) -->
                                    @if($u->role == 'anggota')
                                        @if($u->member_type == 'lama')
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 w-fit">
                                                Anggota Lama
                                            </span>
                                        @elseif($u->member_type == 'baru')
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 w-fit">
                                                Anggota Baru
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 w-fit">
                                                -
                                            </span>
                                        @endif
                                    @endif

                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">
                                    {{ $u->name }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-medium text-gray-900">
                                  {{ $u->kta_id }}
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $u->username }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $u->email }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $u->phone ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $u->gender == 'L' ? 'Laki-laki' : ($u->gender == 'P' ? 'Perempuan' : '-') }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $u->birth_date ? \Carbon\Carbon::parse($u->birth_date)->translatedFormat('d M Y') : '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $u->age ? $u->age . ' Th' : '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 bg-cyan-100 text-cyan-700 rounded-full text-xs font-medium">{{ $u->point ?? 0 }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $u->city ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $u->kecamatan ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $u->kelurahan ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $u->occupation ?? '-' }}</td>
                          <!-- Kolom Anggota Keluarga (lewat relasi langsung) -->
                            <td class="px-4 py-3 text-center">
                                @php
                                    $familyCount = $u->familyMembers->count() ?? 0;
                                @endphp
                                @if($familyCount > 0)
                                    <button 
                                        onclick='showFamilyDetailModal(@json($u))'
                                        class="text-blue-600 hover:text-blue-800 font-medium underline text-sm"
                                        title="Klik untuk melihat detail anggota keluarga">
                                        {{ $familyCount }} orang
                                    </button>
                                @else
                                    <span class="text-gray-500 text-sm">0 orang</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $u->join_date ? \Carbon\Carbon::parse($u->join_date)->translatedFormat('d M Y') : '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $u->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($u->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($u->profile_photo_url)
                                    <img src="{{ $u->profile_photo_url }}" class="w-10 h-10 rounded-full object-cover" alt="Profile">
                                @else
                                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-400 text-xs"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $u->expired_at ? \Carbon\Carbon::parse($u->expired_at)->translatedFormat('d M Y') : '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col gap-1">
                                    <button onclick='showEditModal(@json($u))' class="px-2 py-1 bg-yellow-500 text-white rounded text-xs hover:bg-yellow-600 transition-colors">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button onclick="showPointModal({{ $u->id }}, '{{ $u->name }}', {{ $u->point ?? 0 }})" class="px-2 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600 transition-colors">
                                        <i class="fas fa-plus"></i> Point
                                    </button>
                                    <form action="{{ route('user.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full px-2 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600 transition-colors">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="16" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-users text-4xl mb-2 text-gray-300"></i>
                                <p>Tidak ada data anggota.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Create User Modal
  // Modal Detail Anggota Keluarga (menggunakan data dari relasi yang sudah di-load)
function showFamilyDetailModal(user) {
    let familyListHtml = '';

    if (!user.family_members || user.family_members.length === 0) {
        familyListHtml = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-users-slash text-4xl mb-3 opacity-50"></i>
                <p>Belum ada anggota keluarga yang terdaftar untuk user ini</p>
            </div>
        `;
    } else {
        familyListHtml = user.family_members.map(member => `
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h5 class="font-semibold text-gray-800">${member.name_ktp || '-'}</h5>
                        <p class="text-sm text-gray-600 mt-1">
                            <span class="font-medium">Hubungan:</span> ${member.relationship || '-'}<br>
                            <span class="font-medium">Tanggal Lahir:</span> ${member.birth_date }<br>
                            <span class="font-medium">Panggilan:</span> ${member.nickname || '-'}<br>
                            <span class="font-medium">Alamat:</span> ${member.address || '-'}
                        </p>
                    </div>
                </div>
            </div>
        `).join('');
    }

    Swal.fire({
        title: `<strong>Anggota Keluarga - ${user.name || 'User'}</strong>`,
        html: `
            <div class="text-left p-4 max-h-[60vh] overflow-y-auto">
                <p class="text-sm text-gray-600 mb-4">
                    Total anggota keluarga: <strong>${user.family_members?.length || 0} orang</strong>
                </p>
                <div class="space-y-3">
                    ${familyListHtml}
                </div>
            </div>
        `,
        width: '720px',
        showConfirmButton: true,
        confirmButtonText: 'Tutup',
        confirmButtonColor: '#3E9A3E',
        customClass: {
            popup: 'rounded-xl shadow-2xl',
            title: 'text-xl font-bold text-gray-800 border-b pb-3 mb-4',
            htmlContainer: 'p-0'
        }
    });
}
    function showCreateModal() {
        Swal.fire({
            title: '<strong>Tambah User (Anggota)</strong>',
            html: `
                <form id="createUserForm" class="text-left p-4">
                    <div class="space-y-3">
                      <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor KTA (Opsional)</label>
                            <input type="text" name="kta_id"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                   placeholder="Isi jika sudah punya KTA">
                            <p class="text-xs text-gray-400 mt-1">
                                Kosongkan jika ingin dibuat otomatis (Anggota Baru)
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" name="name" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm"
                                   placeholder="Masukkan nama lengkap">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="email" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm"
                                   placeholder="email@contoh.com">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                                <input type="text" name="phone" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm"
                                       placeholder="08xxxxxxxxxx">
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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                <input type="date" name="birth_date" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                                <input type="text" name="city" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm"
                                       placeholder="Jakarta">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea name="address" rows="2" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm"
                                      placeholder="Alamat lengkap"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                                <input type="text" name="occupation" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm"
                                       placeholder="Wiraswasta">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                                <select name="role" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                                    <option value="anggota">Anggota</option>
                                    <option value="publik">Publik</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                                <input type="password" name="password" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm"
                                       placeholder="Minimal 8 karakter">
                            </div>
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
            width: '600px',
            showCancelButton: true,
            confirmButtonText: 'Tambah',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#3E9A3E',
            cancelButtonColor: '#6b7280',
            customClass: {
                popup: 'rounded-xl',
                htmlContainer: 'p-0'
            },
            preConfirm: () => {
                const form = document.getElementById('createUserForm');
                const formData = new FormData(form);

                return fetch('{{ route("user.store") }}', {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Gagal menambahkan user');
                        });
                    }
                    return response.json();
                })
                .catch(error => {
                    Swal.showValidationMessage(`Error: ${error.message}`);
                    return false;
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'User berhasil ditambahkan',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            }
        });
    }

    // Edit User Modal
    function showEditModal(user) {

    const birthDate = user.birth_date
    ? new Date(user.birth_date).toISOString().split('T')[0]
    : '';

        Swal.fire({
            title: '<strong>Edit User (Anggota)</strong>',
            html: `
                <form id="editUserForm" class="text-left p-4">
                    <div class="space-y-3">
                      <div>
                          <label class="block text-sm font-medium text-gray-700 mb-1">Nomor KTA</label>
                          <input type="text" name="kta_id" value="${user.kta_id || ''}"
                                 class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                      </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <input type="text" name="name" value="${user.name || ''}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="${user.email || ''}" 
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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                                <input type="date" name="birth_date" value="${birthDate}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                                <input type="text" name="city" value="${user.city || ''}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea name="address" rows="2" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">${user.address || ''}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                                <input type="text" name="occupation" value="${user.occupation || ''}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <select name="role" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                                    <option value="anggota" ${user.role == 'anggota' ? 'selected' : ''}>Anggota</option>
                                    <option value="publik" ${user.role == 'publik' ? 'selected' : ''}>Publik</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                                    <option value="aktif" ${user.status == 'aktif' ? 'selected' : ''}>Aktif</option>
                                    <option value="nonaktif" ${user.status == 'nonaktif' ? 'selected' : ''}>Nonaktif</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-gray-400 text-xs">(opsional)</span></label>
                                <input type="password" name="password" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#3E9A3E] focus:border-transparent text-sm">
                            </div>
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
            width: '600px',
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#3E9A3E',
            cancelButtonColor: '#6b7280',
            customClass: {
                popup: 'rounded-xl',
                htmlContainer: 'p-0'
            },
            preConfirm: () => {
                const form = document.getElementById('editUserForm');
                const formData = new FormData(form);
                formData.append('_method', 'PUT');

                return fetch('{{ route("user.update", ":id") }}'.replace(':id', user.id), {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Gagal mengupdate user');
                        });
                    }
                    return response.json();
                })
                .catch(error => {
                    Swal.showValidationMessage(`Error: ${error.message}`);
                    return false;
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'User berhasil diupdate',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            }
        });
    }

    // Add Point Modal
    function showPointModal(userId, userName, currentPoint) {
        Swal.fire({
            title: `<div class="text-left">
                        <h3 class="text-xl font-bold text-gray-800">Kelola Point - ${userName}</h3>
                    </div>`,
            html: `
                <div class="text-left p-4">
                    <div class="bg-cyan-50 border border-cyan-200 p-3 rounded-lg mb-4">
                        <p class="text-sm text-gray-700">
                            <strong>Total Point Saat Ini:</strong>
                            <span class="ml-2 px-3 py-1 bg-cyan-500 text-white rounded-full text-sm font-bold">${currentPoint}</span>
                        </p>
                    </div>

                    <form id="pointForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kegiatan (Kategori Point)</label>
                            <div class="flex gap-2">
                                <select name="point_kategori_id" id="pointKategori" required
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                                    <option value="">-- Pilih Kategori --</option>
                                </select>
                                <button type="button" onclick="addKegiatan()"
                                        class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors text-sm font-medium whitespace-nowrap">
                                    Tambah Kegiatan
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Riwayat Point</h4>
                        <div id="pointHistory" class="space-y-2 max-h-64 overflow-y-auto">
                            <p class="text-sm text-gray-500 text-center py-4">Memuat riwayat...</p>
                        </div>
                    </div>
                </div>
            `,
            width: '600px',
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                popup: 'rounded-xl',
                htmlContainer: 'p-0',
                title: 'text-left w-full'
            },
            didOpen: () => {
                // Load kategori point
                fetch('/api/point-kategoris')
                    .then(res => res.json())
                    .then(data => {
                        const select = document.getElementById('pointKategori');
                        select.innerHTML = '<option value="">-- Pilih Kategori --</option>';
                        
                        if (!data || data.length === 0) {
                            return;
                        }
                        
                        data.forEach(kat => {
                            const opt = new Option(`${kat.name} (${kat.point >= 0 ? '+' : ''}${kat.point} Point)`, kat.id);
                            opt.dataset.point = kat.point;
                            opt.dataset.name = kat.name;
                            select.add(opt);
                        });
                    })
                    .catch(err => {
                        Swal.showValidationMessage('Gagal memuat kategori point');
                    });

                // Load riwayat point
                loadPointHistory(userId);
            }
        });

        // Function untuk tambah kegiatan
        window.addKegiatan = function() {
            const select = document.getElementById('pointKategori');
            const selectedOption = select.selectedOptions[0];

            if (!select.value) {
                Swal.showValidationMessage('Pilih kategori terlebih dahulu');
                return;
            }

            const kategoriId = select.value;
            const kategoriName = selectedOption.dataset.name;
            const point = selectedOption.dataset.point;

            // Kirim request untuk tambah point
            fetch('/user-point/store', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    user_id: userId,
                    point_kategori_id: kategoriId,
                    point: point
                })
            })
            .then(res => {
                if (!res.ok) throw new Error('Gagal menambahkan point');
                return res.json();
            })
            .then(data => {
                // Update total point di header
                const newTotal = parseInt(currentPoint) + parseInt(point);
                currentPoint = newTotal;
                const pointBadge = document.querySelector('.bg-cyan-500');
                if (pointBadge) {
                    pointBadge.textContent = newTotal;
                }

                // Reset select
                select.value = '';

                // Show success notification dengan simple alert di dalam modal
                const successMsg = document.createElement('div');
                successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-[10000] flex items-center gap-2';
                successMsg.innerHTML = '<i class="fas fa-check-circle"></i><span>Kegiatan berhasil ditambahkan</span>';
                document.body.appendChild(successMsg);
                
                setTimeout(() => {
                    successMsg.remove();
                }, 2000);

                // Reload riwayat setelah delay kecil
                setTimeout(() => {
                    loadPointHistory(userId);
                }, 300);
            })
            .catch(err => {
                const errorMsg = document.createElement('div');
                errorMsg.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg z-[10000] flex items-center gap-2';
                errorMsg.innerHTML = `<i class="fas fa-exclamation-circle"></i><span>${err.message}</span>`;
                document.body.appendChild(errorMsg);
                
                setTimeout(() => {
                    errorMsg.remove();
                }, 3000);
            });
        };

        // Function untuk load riwayat point
        function loadPointHistory(userId) {
            fetch(`/api/user-points/${userId}`)
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('pointHistory');
                    
                    // Cek apakah container masih ada (modal belum ditutup)
                    if (!container) {
                        return;
                    }

                    if (data.length === 0) {
                        container.innerHTML = '<p class="text-sm text-gray-500 text-center py-4">Belum ada riwayat point</p>';
                        return;
                    }

                    container.innerHTML = data.map(item => {
                        const pointClass = item.point >= 0 ? 'text-green-600' : 'text-red-600';
                        const pointSign = item.point >= 0 ? '+' : '';
                        const date = new Date(item.created_at).toLocaleString('id-ID', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        return `
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h5 class="font-semibold text-gray-800 text-sm">${item.kategori_name || 'N/A'}</h5>
                                        <p class="text-xs text-gray-600 mt-1">Point: <span class="${pointClass} font-bold">${pointSign}${item.point}</span></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500">${date}</p>
                                        <p class="text-xs text-gray-400 mt-1">Ditambahkan oleh: ${item.added_by || 'Admin'}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('');
                })
                .catch(err => {
                    const container = document.getElementById('pointHistory');
                    if (container) {
                        container.innerHTML = '<p class="text-sm text-red-500 text-center py-4">Gagal memuat riwayat</p>';
                    }
                });
        }
    }

    // Filter Toggle
    function toggleFilter() {
        const filter = document.getElementById('advancedFilter');
        const icon = document.getElementById('filterIcon');
        filter.classList.toggle('hidden');
        icon.classList.toggle('fa-chevron-down');
        icon.classList.toggle('fa-chevron-up');
    }

    // SweetAlert Notifications
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session("success") }}',
            timer: 1500,
            showConfirmButton: false
        });
    @endif

    @if(session('point_success'))
        Swal.fire({
            icon: 'success',
            title: 'Point Ditambahkan',
            text: '{{ session("point_success") }}',
            timer: 1500,
            showConfirmButton: false
        });
    @endif
</script>

</script>
@endpush
