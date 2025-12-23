<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('master_penukaran_poin', function (Blueprint $table) {
            $table->id();

            $table->string('produk');
            // Nama produk penukaran (contoh: Rinso)

            $table->text('keterangan')->nullable();
            // Berlaku s/d, syarat, dll

            $table->string('image')->nullable();
            // Path atau filename gambar produk

            $table->unsignedInteger('jumlah_poin');
            // Jumlah poin yang dibutuhkan

            $table->boolean('is_active')->default(true);
            // Untuk aktif/nonaktif tanpa hapus data

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_penukaran_poin');
    }
};
