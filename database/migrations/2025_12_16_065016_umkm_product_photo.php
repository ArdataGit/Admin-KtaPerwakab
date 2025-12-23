<?php

// database/migrations/xxxx_xx_xx_create_umkm_product_photo_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('umkm_product_photo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('umkm_product')
                ->cascadeOnDelete();
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umkm_product_photo');
    }
};
