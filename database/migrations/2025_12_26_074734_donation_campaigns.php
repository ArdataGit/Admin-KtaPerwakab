<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('donation_campaigns', function (Blueprint $table) {
            $table->id();

            // Konten campaign
            $table->string('title');
            $table->text('description');

            // Foto utama
            $table->string('thumbnail')->nullable();

            // Periode campaign
            $table->date('start_date');
            $table->date('end_date')->nullable();

            // Status & audit
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_campaigns');
    }
};
