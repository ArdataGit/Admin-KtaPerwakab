@extends('layouts.app')

@section('title', 'Tambah UMKM')

@section('page-title', 'Tambah UMKM')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah UMKM</h2>
            <p class="text-sm text-gray-600 mt-1">Daftarkan UMKM baru</p>
        </div>
        <a href="{{ route('umkm.index') }}"
           class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-[#3E9A3E] to-[#85C955] p-6">
            <h3 class="text-xl font-semibold text-white">
                <i class="fas fa-plus-circle mr-2"></i>Form Tambah UMKM
            </h3>
        </div>

        <form action="{{ route('umkm.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            @include('pages.master.umkm.form', ['data' => null])

            <!-- Action Buttons -->
            <div class="flex gap-3 justify-end mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('umkm.index') }}"
                   class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-[#3E9A3E] to-[#85C955] text-white rounded-lg hover:shadow-lg transition-all">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>

@endsection
