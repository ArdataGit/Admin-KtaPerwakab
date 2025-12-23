<div class="form-group">
    <label>Nama Almarhum</label>
    <input type="text" name="nama_almarhum" class="form-control"
        value="{{ old('nama_almarhum', $data->nama_almarhum ?? '') }}" required>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label>Usia</label>
        <input type="number" name="usia" class="form-control" value="{{ old('usia', $data->usia ?? '') }}">
    </div>

    <div class="form-group col-md-6">
        <label>Asal</label>
        <input type="text" name="asal" class="form-control" value="{{ old('asal', $data->asal ?? '') }}">
    </div>
</div>

<div class="form-group">
    <label>Judul</label>
    <input type="text" name="judul" class="form-control" value="{{ old('judul', $data->judul ?? '') }}" required>
</div>

<div class="form-group">
    <label>Isi (Editor)</label>
    <textarea name="isi" id="editor" class="form-control" rows="5">
        {{ old('isi', $data->isi ?? '') }}
    </textarea>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label>Tanggal Wafat</label>
        <input type="date" name="tanggal_wafat" class="form-control"
            value="{{ old('tanggal_wafat', optional($data->tanggal_wafat ?? null)->format('Y-m-d')) }}" required>
    </div>

    <div class="form-group col-md-6">
        <label>Tanggal Publish</label>
        <input type="datetime-local" name="tanggal_publish" class="form-control"
            value="{{ old('tanggal_publish', optional($data->tanggal_publish ?? null)->format('Y-m-d\TH:i')) }}"
            required>
    </div>
</div>

<div class="form-group">
    <label>Foto (1 Foto)</label>
    <input type="file" name="foto" class="form-control-file">

    @if (!empty($data?->foto))
        <div class="mt-2">
            <small class="text-muted d-block">Foto saat ini:</small>
            <img src="{{ asset('storage/' . $data->foto) }}" width="80" class="rounded shadow-sm">
        </div>
    @endif
</div>

<div class="form-group">
    <label>Status</label>
    <select name="is_active" class="form-control">
        <option value="1" {{ old('is_active', $data->is_active ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
        <option value="0" {{ old('is_active', $data->is_active ?? 1) == 0 ? 'selected' : '' }}>Nonaktif</option>
    </select>
</div>

<!-- CKEditor CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
</script>