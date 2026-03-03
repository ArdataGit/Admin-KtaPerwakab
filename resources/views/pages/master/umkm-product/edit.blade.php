@extends('layouts.app')

@section('title', 'Edit Produk UMKM')

@section('page-title', 'Edit Produk UMKM')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Produk UMKM</h2>
            <p class="text-sm text-gray-600 mt-1">{{ $umkm->user->name ?? 'Pemilik tidak ditemukan' }}</p>
        </div>
        <a href="{{ route('umkm.products.index', $umkm->id) }}"
           class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-6">
            <h3 class="text-xl font-semibold text-white">
                <i class="fas fa-edit mr-2"></i>Form Edit Produk
            </h3>
        </div>

        <form id="updateProductForm" action="{{ route('umkm.products.update', [$umkm->id, $product->id]) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            @include('pages.master.umkm-product.form', ['data' => $product, 'umkm' => $umkm])

            <!-- Action Buttons -->
            <div class="flex gap-3 justify-end mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('umkm.products.index', $umkm->id) }}"
                   class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" id="submitBtn"
                        class="px-6 py-2.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-all">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </div>

    {{-- Hidden forms untuk delete foto (di luar form utama) --}}
    @if($product->photos->count() > 0)
        @foreach($product->photos as $photo)
            <form id="delete-photo-form-{{ $photo->id }}" 
                  action="{{ route('umkm.product-photos.destroy', $photo->id) }}" 
                  method="POST" 
                  style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    @endif

@endsection

@push('scripts')
<script>
    function deletePhoto(photoId) {
        if (confirm('Hapus foto ini?')) {
            document.getElementById('delete-photo-form-' + photoId).submit();
        }
    }

    // Form submission handler
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('updateProductForm');
        const submitBtn = document.getElementById('submitBtn');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            });
        }
    });
</script>
@endpush
