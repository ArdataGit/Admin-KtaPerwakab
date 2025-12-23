@extends('layouts.app')

@section('content')

    <div class="d-flex justify-content-between mb-3">
        <div>
            <h4>Produk UMKM</h4>
            <small class="text-muted">{{ $umkm->umkm_name }}</small>
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
                        <th>Harga</th>
                        <th width="12%">Foto</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $i => $product)
                        <tr>
                            <td>{{ $products->firstItem() + $i }}</td>

                            <td>{{ $product->product_name }}</td>

                            <td>
                                @if ($product->price)
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                @else
                                    <span class="text-muted">-</span>
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
                                <button class="btn btn-sm btn-warning" data-toggle="modal"
                                    data-target="#modalEditProduct{{ $product->id }}">
                                    Edit
                                </button>

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
                            <td colspan="5" class="text-center text-muted">
                                Produk belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @foreach ($products as $product)
                <div class="modal fade" id="modalEditProduct{{ $product->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <form action="{{ route('umkm.products.update', [$umkm->id, $product->id]) }}" method="POST"
                            enctype="multipart/form-data" class="modal-content">
                            @csrf
                            @method('PUT')

                            <div class="modal-header">
                                <h5 class="modal-title">Edit Produk</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">

                                <div class="form-group">
                                    <label>Nama Produk</label>
                                    <input type="text" name="product_name" class="form-control"
                                        value="{{ $product->product_name }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Harga</label>
                                    <input type="number" name="price" class="form-control" value="{{ $product->price }}">
                                </div>

                                <div class="form-group">
                                    <label>Link YouTube</label>
                                    <input type="url" name="youtube_link" class="form-control"
                                        value="{{ $product->youtube_link }}">
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea name="description" class="form-control"
                                        rows="3">{{ $product->description }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Tambah Foto Baru</label>
                                    <input type="file" name="photos[]" multiple class="form-control-file">
                                </div>

                                @if ($product->photos->count())
                                    <label>Foto Produk</label>
                                    <div class="row">
                                        @foreach ($product->photos as $photo)
                                            <div class="col-4 mb-2 text-center">
                                                <img src="{{ asset('storage/' . $photo->file_path) }}" class="img-fluid rounded mb-1"
                                                    style="max-height:120px;object-fit:cover">
                                                <form action="{{ route('umkm.product-photos.destroy', $photo->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus foto ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger btn-block">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button class="btn btn-warning">Update</button>
                            </div>

                        </form>
                    </div>
                </div>
            @endforeach

            <!-- MODAL CREATE PRODUK -->
            <div class="modal fade" id="modalCreateProduct" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">

                    <form action="{{ route('umkm.products.store', $umkm->id) }}" method="POST" enctype="multipart/form-data"
                        class="modal-content">

                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Produk</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">

                            <div class="form-group">
                                <label>Nama Produk</label>
                                <input type="text" name="product_name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Harga</label>
                                <input type="number" name="price" class="form-control" placeholder="Contoh: 25000">
                            </div>

                            <div class="form-group">
                                <label>Link YouTube</label>
                                <input type="url" name="youtube_link" class="form-control"
                                    placeholder="https://youtube.com/...">
                            </div>

                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="form-group">
                                <label>Foto Produk</label>
                                <input type="file" name="photos[]" class="form-control-file" multiple>
                                <small class="text-muted">Bisa upload lebih dari satu foto</small>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                Batal
                            </button>
                            <button class="btn btn-primary">
                                Simpan
                            </button>
                        </div>

                    </form>
                </div>
            </div>


            {{ $products->links('pagination::bootstrap-4') }}

        </div>
    </div>

@endsection