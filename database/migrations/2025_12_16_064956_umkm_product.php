<?php

// database/migrations/xxxx_xx_xx_create_umkm_product_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('umkm_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_id')
                ->constrained('umkm')
                ->cascadeOnDelete();
            $table->string('product_name');
            $table->decimal('price', 15, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('youtube_link')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umkm_product');
    }
};
