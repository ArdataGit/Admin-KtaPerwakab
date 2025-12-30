<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cek apakah kolom user_id sudah ada
        if (!Schema::hasColumn('umkm', 'user_id')) {
            Schema::table('umkm', function (Blueprint $table) {
                // Tambah kolom user_id dulu (nullable sementara)
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            });
        }

        // Hapus data lama jika ada (karena tidak bisa dimapping ke user)
        DB::table('umkm')->truncate();

        // Hapus kolom umkm_name jika masih ada
        if (Schema::hasColumn('umkm', 'umkm_name')) {
            Schema::table('umkm', function (Blueprint $table) {
                $table->dropColumn('umkm_name');
            });
        }

        Schema::table('umkm', function (Blueprint $table) {
            // Ubah user_id jadi required dan tambah foreign key
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('umkm', function (Blueprint $table) {
            // Hapus foreign key dan kolom user_id
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            
            // Kembalikan kolom umkm_name
            $table->string('umkm_name')->after('id');
        });
    }
};
