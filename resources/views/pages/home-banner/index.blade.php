@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Banner Home</h4>
    <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreateBanner">
        Tambah Banner
    </button>
</div>

<div class="card shadow mb-4">
    <div class="card-body table-responsive">

        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>Judul</th>
                    <th>Subjudul</th>
                    <th>Posisi</th>
                    <th>Status</th>
                    <th width="12%">Gambar</th>
                    <th width="18%">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($banners as $i => $banner)
                    <tr>
                        <td>{{ $banners->firstItem() + $i }}</td>

                        <td>{{ $banner->title ?? '-' }}</td>
                        <td>{{ $banner->subtitle ?? '-' }}</td>

                        <td>{{ $banner->position }}</td>

                        <td>
                            @if ($banner->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <img src="{{ $banner->image ? asset('storage/'.$banner->image) : asset('images/no-image.png') }}"
                                 width="80"
                                 class="rounded shadow-sm">
                        </td>

                        <td>
                            <button class="btn btn-sm btn-warning"
                                    data-toggle="modal"
                                    data-target="#modalEditBanner{{ $banner->id }}">
                                Edit
                            </button>

                            <form action="{{ route('home-banner.destroy', $banner->id) }}"
                                  method="POST"
                                  style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hapus banner ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Banner belum tersedia
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $banners->links('pagination::bootstrap-4') }}

    </div>
</div>

{{-- ================= MODAL EDIT BANNER ================= --}}
@foreach ($banners as $banner)
<div class="modal fade" id="modalEditBanner{{ $banner->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('home-banner.update', $banner->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title">Edit Banner</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="title" class="form-control"
                           value="{{ $banner->title }}">
                </div>

                <div class="form-group">
                    <label>Subjudul</label>
                    <input type="text" name="subtitle" class="form-control"
                           value="{{ $banner->subtitle }}">
                </div>

                <div class="form-group">
                    <label>Link</label>
                    <input type="text" name="link" class="form-control"
                           value="{{ $banner->link }}">
                </div>

                <div class="form-group">
                    <label>Posisi</label>
                    <input type="number" name="position" class="form-control"
                           min="0" step="1"
                           value="{{ $banner->position }}">
                </div>

                <div class="form-group">
                    <label>Periode Tampil</label>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="datetime-local"
                                   name="start_at"
                                   class="form-control"
                                   value="{{ optional($banner->start_at)->format('Y-m-d\TH:i') }}">
                        </div>
                        <div class="col-md-6">
                            <input type="datetime-local"
                                   name="end_at"
                                   class="form-control"
                                   value="{{ optional($banner->end_at)->format('Y-m-d\TH:i') }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Gambar</label>
                    <input type="file" name="image" class="form-control-file"
                           onchange="this.nextElementSibling.src = window.URL.createObjectURL(this.files[0])">

                    <img src="{{ asset('storage/'.$banner->image) }}"
                         width="120"
                         class="rounded shadow-sm mt-2">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1" {{ $banner->is_active ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$banner->is_active ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    Batal
                </button>
                <button class="btn btn-warning">
                    Update
                </button>
            </div>

        </form>

    </div>
</div>
@endforeach

{{-- ================= MODAL CREATE BANNER ================= --}}
<div class="modal fade" id="modalCreateBanner" tabindex="-1">
    <div class="modal-dialog modal-lg">

        <form action="{{ route('home-banner.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Tambah Banner</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="title" class="form-control">
                </div>

                <div class="form-group">
                    <label>Subjudul</label>
                    <input type="text" name="subtitle" class="form-control">
                </div>

                <div class="form-group">
                    <label>Link (Opsional)</label>
                    <input type="text" name="link" class="form-control"
                           placeholder="https://">
                </div>

                <div class="form-group">
                    <label>Posisi</label>
                    <input type="number" name="position" class="form-control"
                           value="0" min="0" step="1">
                </div>

                <div class="form-group">
                    <label>Periode Tampil (Opsional)</label>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="datetime-local" name="start_at" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <input type="datetime-local" name="end_at" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Gambar Banner</label>
                    <input type="file" name="image"
                           class="form-control-file"
                           accept="image/*"
                           required>

                    <small class="text-muted">
                        Rekomendasi ukuran: 1200x400 px
                    </small>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="is_active" class="form-control">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    Batal
                </button>
                <button class="btn btn-primary">
                    Simpan
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
