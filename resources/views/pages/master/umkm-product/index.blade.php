@extends('layouts.app')
@php
    $categories = [
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

@section('content')

    {{-- Alert Success --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Alert Error (hanya sekali di atas) --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="d-flex justify-content-between mb-3">
        <div>
            <h4>Produk UMKM</h4>
            <h6 class="text-muted">{{ $umkm->user->name ?? 'Pemilik tidak ditemukan' }}</h6>
        </div>

        <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreateProduct">
            Tambah Produk
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Nama Produk</th>
                      	<th>Kategori</th>
                        <th>Harga</th>
                        <th width="10%">Status</th>
                        <th width="12%">Foto</th>
                        <th width="25%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $i => $product)
                        <tr>
                            <td>{{ $products->firstItem() + $i }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>
                                {{ $product->category ?? '-' }}
                            </td>

                            <td>
                                @if ($product->price)
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($product->status === 'approved')
                                    <span class="badge badge-success">Disetujui</span>
                                @elseif($product->status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if ($product->photos->count())
                                    <img src="{{ asset('storage/' . $product->photos->first()->file_path) }}" width="60"
                                        class="rounded shadow-sm">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                {{-- Tombol Approve/Reject (hanya untuk pending) --}}
                                @if($product->status === 'pending')
                                    <form action="{{ route('umkm.products.approve', [$umkm->id, $product->id]) }}" 
                                          method="POST" style="display:inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" 
                                                onclick="return confirm('Setujui produk ini?')">
                                            Setujui
                                        </button>
                                    </form>

                                    <form action="{{ route('umkm.products.reject', [$umkm->id, $product->id]) }}" 
                                          method="POST" style="display:inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Tolak produk ini?')">
                                            Tolak
                                        </button>
                                    </form>
                                @endif

                                {{-- Tombol Edit --}}
                                <button class="btn btn-sm btn-warning" data-toggle="modal"
                                    data-target="#modalEditProduct{{ $product->id }}">
                                    Edit
                                </button>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('umkm.products.destroy', [$umkm->id, $product->id]) }}" method="POST"
                                    style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Hapus produk ini?')" class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Produk belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- MODAL EDIT PRODUK (loop) --}}
            @foreach ($products as $product)
                <div class="modal fade" id="modalEditProduct{{ $product->id }}" tabindex="-1"
                     aria-labelledby="modalEditProductLabel{{ $product->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form action="{{ route('umkm.products.update', [$umkm->id, $product->id]) }}"
                                  method="POST" enctype="multipart/form-data" id="formEdit{{ $product->id }}">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalEditProductLabel{{ $product->id }}">Edit Produk</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <!-- TIDAK ADA ALERT ERROR DI SINI (sudah dipindah ke atas) -->

                                    <div class="form-group">
                                        <label>Nama Produk <span class="text-danger">*</span></label>
                                        <input type="text" name="product_name"
                                               class="form-control @error('product_name') is-invalid @enderror"
                                               value="{{ old('product_name', $product->product_name) }}" required>
                                        @error('product_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Kategori <span class="text-danger">*</span></label>
                                        <select name="category"
                                                class="form-control @error('category') is-invalid @enderror"
                                                required>
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat }}"
                                                    {{ old('category', $product->category) === $cat ? 'selected' : '' }}>
                                                    {{ $cat }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                    <div class="form-group">
                                        <label>Harga <span class="text-danger">*</span></label>
                                        <input type="number" name="price"
                                               class="form-control @error('price') is-invalid @enderror"
                                               value="{{ old('price', $product->price) }}" required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Link YouTube</label>
                                        <input type="url" name="youtube_link"
                                               class="form-control @error('youtube_link') is-invalid @enderror"
                                               value="{{ old('youtube_link', $product->youtube_link) }}">
                                        @error('youtube_link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Deskripsi</label>
                                        <textarea name="description"
                                                  class="form-control @error('description') is-invalid @enderror"
                                                  rows="3">{{ old('description', $product->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Tambah Foto Baru @if($product->photos->count() == 0)<span class="text-danger">*</span>@endif</label>
                                        <input type="file" name="photos[]" multiple
                                               class="form-control-file @error('photos') is-invalid @enderror @error('photos.*') is-invalid @enderror"
                                               @if($product->photos->count() == 0) required @endif>
                                        <small class="text-muted">
                                            @if($product->photos->count() == 0)
                                                Produk belum memiliki foto. Wajib upload minimal 1 foto.
                                            @else
                                                Opsional. Format: JPG, JPEG, PNG (Max: 2MB)
                                            @endif
                                        </small>
                                        @error('photos')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        @error('photos.*')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                   @if ($product->photos->count() > 0)
    <label>Foto Produk Saat Ini</label>
    <div class="row">
        @foreach ($product->photos as $photo)
            <div class="col-4 mb-3 text-center">
                <img src="{{ asset('storage/' . $photo->file_path) }}" 
                     class="img-fluid rounded mb-2" style="max-height:120px; object-fit:cover;">
                
                <button type="button" class="btn btn-sm btn-danger btn-block"
                        onclick="deletePhoto({{ $photo->id }}, this)">
                    Hapus
                </button>
            </div>
        @endforeach
    </div>
@endif
                                </div>

                                <div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    <button type="button" class="btn btn-warning" 
            onclick="submitEditForm({{ $product->id }})">
        Update
    </button>
</div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- MODAL CREATE PRODUK (alert error tetap di sini karena khusus create) --}}
            <div class="modal fade" id="modalCreateProduct" tabindex="-1" role="dialog"
                 aria-labelledby="modalCreateProductLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <form action="{{ route('umkm.products.store', $umkm->id) }}" method="POST"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="modal-header">
                                <h5 class="modal-title" id="modalCreateProductLabel">Tambah Produk</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Terjadi kesalahan!</strong>
                                        <ul class="mb-0 mt-2">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <!-- field create tetap sama -->
                                <div class="form-group">
                                    <label>Nama Produk <span class="text-danger">*</span></label>
                                    <input type="text" name="product_name"
                                           class="form-control @error('product_name') is-invalid @enderror"
                                           value="{{ old('product_name') }}" required>
                                    @error('product_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Kategori <span class="text-danger">*</span></label>
                                    <select name="category"
                                            class="form-control @error('category') is-invalid @enderror"
                                            required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat }}"
                                                {{ old('category') === $cat ? 'selected' : '' }}>
                                                {{ $cat }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Harga <span class="text-danger">*</span></label>
                                    <input type="number" name="price"
                                           class="form-control @error('price') is-invalid @enderror"
                                           value="{{ old('price') }}" placeholder="Contoh: 25000" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Link YouTube</label>
                                    <input type="url" name="youtube_link"
                                           class="form-control @error('youtube_link') is-invalid @enderror"
                                           value="{{ old('youtube_link') }}" placeholder="https://youtube.com/...">
                                    @error('youtube_link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea name="description"
                                              class="form-control @error('description') is-invalid @enderror"
                                              rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Foto Produk <span class="text-danger">*</span></label>
                                    <input type="file" name="photos[]" multiple required
                                           class="form-control-file @error('photos') is-invalid @enderror @error('photos.*') is-invalid @enderror">
                                    <small class="text-muted d-block">Minimal 1 foto, bisa upload lebih dari satu. Format: JPG, JPEG, PNG (Max: 2MB)</small>
                                    @error('photos')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    @error('photos.*')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary"
                                        onclick="console.log('Button Simpan (Create) diklik')">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{ $products->links('pagination::bootstrap-4') }}

        </div>
    </div>
<script>
function submitEditForm(productId) {
    console.log('submitEditForm dipanggil untuk Product ID: ' + productId);

    const form = document.getElementById('formEdit' + productId);
    
    if (form) {
        console.log('Form edit ditemukan, submitting...');
        form.submit();
    } else {
        console.error('Form edit tidak ditemukan!');
    }
}

function deletePhoto(photoId, buttonElement) {
    if (confirm('Hapus foto ini?')) {
        console.log('Menghapus foto ID: ' + photoId);

        // Buat form sementara
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url("master/umkm-product-photo") }}/' + photoId; // sesuaikan base URL jika perlu
        
        // Tambahkan CSRF
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        // Tambahkan method DELETE
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(method);
        
        // Submit form
        document.body.appendChild(form);
        form.submit();
        
        // Optional: disable button sementara
        buttonElement.disabled = true;
        buttonElement.textContent = 'Menghapus...';
    }
}
</script>
@endsection