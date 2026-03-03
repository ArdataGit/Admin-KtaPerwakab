{{-- Pilih User --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Pemilik UMKM <span class="text-red-500">*</span></label>
    <select name="user_id" 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" 
            required>
        <option value="">-- Pilih User --</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}" {{ old('user_id', $data->user_id ?? '') == $user->id ? 'selected' : '' }}>
                {{ $user->name }} ({{ $user->email }})
            </option>
        @endforeach
        {{-- Tampilkan user yang sedang dipilih meskipun sudah punya UMKM (untuk edit) --}}
        @if(isset($data) && $data->user && !$users->contains('id', $data->user_id))
            <option value="{{ $data->user_id }}" selected>
                {{ $data->user->name }} ({{ $data->user->email }})
            </option>
        @endif
    </select>
    <p class="text-xs text-gray-500 mt-2">
        <i class="fas fa-info-circle mr-1"></i>Hanya user yang belum memiliki UMKM yang ditampilkan
    </p>
</div>

{{-- Kategori --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
    <select name="category" 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" 
            required>
        <option value="">-- Pilih Kategori --</option>
        @php
            $categories = [
                'kuliner' => 'Kuliner',
                'fashion' => 'Fashion',
                'kerajinan' => 'Kerajinan',
                'jasa' => 'Jasa',
                'pertanian' => 'Pertanian',
                'lainnya' => 'Lainnya',
            ];
        @endphp
        @foreach ($categories as $key => $label)
            <option value="{{ $key }}" {{ old('category', $data->category ?? '') === $key ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>

{{-- Lokasi --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
    <input type="text" name="location" 
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" 
           value="{{ old('location', $data->location ?? '') }}"
           placeholder="Contoh: Jl. Merdeka No. 123, Jakarta">
</div>

{{-- WhatsApp --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp</label>
    <input type="text" name="contact_wa" 
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" 
           value="{{ old('contact_wa', $data->contact_wa ?? '') }}"
           placeholder="Contoh: 628123456789">
    <p class="text-xs text-gray-500 mt-2">
        <i class="fas fa-info-circle mr-1"></i>Format: 628123456789 (tanpa tanda +)
    </p>
</div>

{{-- Logo --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
    <input type="file" name="logo" 
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" 
           accept="image/jpeg,image/png,image/jpg">
    
    <p class="text-xs text-gray-500 mt-2">
        <i class="fas fa-info-circle mr-1"></i>Format: JPG, JPEG, PNG (Max 2MB)
    </p>

    @if (!empty($data?->logo))
        <div class="mt-3 p-3 bg-gray-50 rounded-lg">
            <p class="text-xs text-gray-600 mb-2">Logo saat ini:</p>
            <img src="{{ asset('storage/' . $data->logo) }}" 
                 class="w-24 h-24 object-cover rounded-lg shadow-md" 
                 alt="Current logo">
        </div>
    @endif
</div>

{{-- Deskripsi --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
    <textarea name="description" 
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" 
              rows="4"
              placeholder="Deskripsikan UMKM Anda...">{{ old('description', $data->description ?? '') }}</textarea>
</div>
