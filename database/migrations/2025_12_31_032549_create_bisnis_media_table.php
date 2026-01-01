<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bisnis_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bisnis_id');
            $table->enum('type', ['image', 'video', 'youtube', 'embed']);
            $table->string('file_path')->nullable();
            $table->string('url', 500)->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();

            // Index    
            $table->index('bisnis_id');

            // Foreign key
            $table->foreign('bisnis_id', 'bisnis_media_bisnis_id_fk')
                  ->references('id')
                  ->on('bisnis')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bisnis_media');
    }
};
