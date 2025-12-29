<div class="form-group">
    <label>Judul Campaign</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $data->title ?? '') }}" required>
</div>

<div class="form-group">
    <label>Deskripsi</label>
    <textarea
        name="description"
        id="description-editor"
        class="form-control"
        rows="6"
        required
    >{{ old('description', $data->description ?? '') }}</textarea>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label>Tanggal Mulai</label>
        <input type="date" name="start_date" class="form-control"
            value="{{ old('start_date', optional($data->start_date ?? null)->format('Y-m-d')) }}" required>
    </div>

    <div class="form-group col-md-6">
        <label>Tanggal Selesai (Opsional)</label>
        <input type="date" name="end_date" class="form-control"
            value="{{ old('end_date', optional($data->end_date ?? null)->format('Y-m-d')) }}">
    </div>
</div>

<div class="form-group">
    <label>Thumbnail</label>
    <input type="file" name="thumbnail" class="form-control-file" accept="image/jpeg,image/png,image/webp">

    <small class="text-muted">
        Format: JPG, PNG, WEBP (Max 2MB)
    </small>

    @if (!empty($data?->thumbnail))
        <div class="mt-2">
            <small class="text-muted d-block">Thumbnail saat ini:</small>
            <img src="{{ asset('storage/' . $data->thumbnail) }}" width="80" class="rounded shadow-sm">
        </div>
    @endif
</div>

<div class="form-group">
    <label>Status</label>
    <select name="is_active" class="form-control">
        <option value="1" {{ old('is_active', $data->is_active ?? 1) == 1 ? 'selected' : '' }}>
            Aktif
        </option>
        <option value="0" {{ old('is_active', $data->is_active ?? 1) == 0 ? 'selected' : '' }}>
            Nonaktif
        </option>
    </select>
</div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/zvffqh7jgnjj2h4r82iobtthnkoyd6hw1zvvt9c33ktqq6r3/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>

<script>
    tinymce.init({
        selector: '#description-editor',
        height: 300,
        menubar: false,
        branding: false,

        plugins: 'lists link image table code',

        toolbar:
            'undo redo | formatselect | ' +
            'bold italic underline | alignleft aligncenter alignright | ' +
            'bullist numlist | link image | removeformat | code',

        content_style: `
            body {
                font-family: Arial, sans-serif;
                font-size: 14px;
            }
        `,

        // PENTING: pastikan value masuk ke textarea saat submit
        setup: function (editor) {
            editor.on('change', function () {
                tinymce.triggerSave();
            });
        }
    });
</script>
@endpush