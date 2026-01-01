@extends('layouts.app')

@section('title', 'Edit UMKM')

@section('page-title', 'Edit UMKM')

@section('content')

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit UMKM</h2>
            <p class="text-sm text-gray-600 mt-1">Perbarui informasi UMKM</p>
        </div>
        <a href="{{ route('umkm.index') }}"
           class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg shadow-md hover:bg-gray-300 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-6">
            <h3 class="text-xl font-semibold text-white">
                <i class="fas fa-edit mr-2"></i>Form Edit UMKM
            </h3>
        </div>

        <form action="{{ route('umkm.update', $umkm->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            @include('pages.master.umkm.form', ['data' => $umkm])

            <!-- Action Buttons -->
            <div class="flex gap-3 justify-end mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('umkm.index') }}"
                   class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-all">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </div>

@endsection
