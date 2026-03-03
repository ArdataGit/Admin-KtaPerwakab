{{-- Nama Produk --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
    <input type="text" name="product_name" 
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('product_name') border-red-500 @enderror" 
           value="{{ old('product_name', $data->product_name ?? '') }}"
           placeholder="Contoh: Kue Lapis Legit"
           required>
    @error('product_name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
{{-- Kategori Produk --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Kategori Produk <span class="text-red-500">*</span>
    </label>

    <select name="category"
        class="w-full px-4 py-2 border border-gray-300 rounded-lg
               focus:ring-2 focus:ring-green-500 focus:border-transparent
               transition-all
               @error('category') border-red-500 @enderror"
        required>

        <option value="">-- Pilih Kategori --</option>

        @php
            $kategoriList = [
                'Makanan',
                'Minuman',
                'Kerajinan',
                'Fashion',
                'Jasa',
                'Pertanian',
                'Perikanan',
                'Lainnya',
            ];
        @endphp

        @foreach ($kategoriList as $kategori)
            <option value="{{ strtolower($kategori) }}"
                {{ old('category', $data->category ?? '') === strtolower($kategori) ? 'selected' : '' }}>
                {{ $kategori }}
            </option>
        @endforeach
    </select>

    @error('category')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>


{{-- Harga --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Harga <span class="text-red-500">*</span></label>
    <input type="number" name="price" 
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('price') border-red-500 @enderror" 
           value="{{ old('price', $data->price ?? '') }}"
           placeholder="Contoh: 150000"
           min="0"
           required>
    @error('price')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Link YouTube --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Link YouTube</label>
    <input type="url" name="youtube_link" 
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('youtube_link') border-red-500 @enderror" 
           value="{{ old('youtube_link', $data->youtube_link ?? '') }}"
           placeholder="https://youtube.com/...">
    <p class="text-xs text-gray-500 mt-2">
        <i class="fas fa-info-circle mr-1"></i>Opsional: Link video produk di YouTube
    </p>
    @error('youtube_link')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Deskripsi --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
    <textarea name="description" 
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('description') border-red-500 @enderror" 
              rows="4"
              placeholder="Deskripsikan produk Anda...">{{ old('description', $data->description ?? '') }}</textarea>
    @error('description')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Foto Produk --}}
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        @if(isset($data) && $data->photos->count() > 0)
            Tambah Foto Baru
        @else
            Foto Produk <span class="text-red-500">*</span>
        @endif
    </label>
    <input type="file" name="photos[]" multiple
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 @error('photos') border-red-500 @enderror @error('photos.*') border-red-500 @enderror" 
           accept="image/jpeg,image/jpg,image/png"
           @if(!isset($data) || $data->photos->count() == 0) required @endif>
    
    <p class="text-xs text-gray-500 mt-2">
        <i class="fas fa-info-circle mr-1"></i>
        @if(isset($data) && $data->photos->count() > 0)
            Opsional. Bisa upload lebih dari satu foto. Format: JPG, JPEG, PNG (Max: 2MB)
        @else
            Minimal 1 foto, bisa upload lebih dari satu. Format: JPG, JPEG, PNG (Max: 2MB)
        @endif
    </p>
    @error('photos')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
    @error('photos.*')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Foto Produk Saat Ini (untuk edit) --}}
@if(isset($data) && $data->photos->count() > 0)
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-3">Foto Produk Saat Ini</label>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($data->photos as $photo)
                <div class="relative group">
                    <img src="{{ asset('storage/' . $photo->file_path) }}" 
                         class="w-full h-32 object-cover rounded-lg shadow-md" 
                         alt="Product photo">
                    <button type="button"
                            onclick="deletePhoto({{ $photo->id }})"
                            class="absolute top-2 right-2 px-3 py-1.5 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors opacity-0 group-hover:opacity-100">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </div>
            @endforeach
        </div>
        <p class="text-xs text-gray-500 mt-2">
            <i class="fas fa-info-circle mr-1"></i>Hover pada foto untuk menampilkan tombol hapus
        </p>
    </div>
@endif
