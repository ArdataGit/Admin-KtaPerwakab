<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Judul Campaign <span class="text-red-500">*</span></label>
    <input type="text" name="title" 
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" 
           value="{{ old('title', $data->title ?? '') }}" 
           placeholder="Masukkan judul campaign"
           required>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
    <textarea id="summernote" name="description" class="w-full">{{ old('description', $data->description ?? '') }}</textarea>
    @error('description')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
        <input type="date" name="start_date" 
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
               value="{{ old('start_date', optional($data->start_date ?? null)->format('Y-m-d')) }}" 
               required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai <span class="text-gray-400 text-xs">(Opsional)</span></label>
        <input type="date" name="end_date" 
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
               value="{{ old('end_date', optional($data->end_date ?? null)->format('Y-m-d')) }}">
    </div>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail</label>
    <input type="file" name="thumbnail" 
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" 
           accept="image/jpeg,image/png,image/webp">

    <p class="text-xs text-gray-500 mt-2">
        <i class="fas fa-info-circle mr-1"></i>Format: JPG, PNG, WEBP (Max 2MB)
    </p>

    @if (!empty($data?->thumbnail))
        <div class="mt-3 p-3 bg-gray-50 rounded-lg">
            <p class="text-xs text-gray-600 mb-2">Thumbnail saat ini:</p>
            <img src="{{ asset('storage/' . $data->thumbnail) }}" 
                 class="w-24 h-24 object-cover rounded-lg shadow-md" 
                 alt="Current thumbnail">
        </div>
    @endif
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
    <select name="is_active" 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
        <option value="1" {{ old('is_active', $data->is_active ?? 1) == 1 ? 'selected' : '' }}>
            <i class="fas fa-check-circle"></i> Aktif
        </option>
        <option value="0" {{ old('is_active', $data->is_active ?? 1) == 0 ? 'selected' : '' }}>
            <i class="fas fa-times-circle"></i> Nonaktif
        </option>
    </select>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 300,
            placeholder: 'Masukkan deskripsi campaign...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],  // Hanya link, tanpa picture & video
                ['view', ['codeview', 'help']]
            ]
        });
    });
</script>
@endpush