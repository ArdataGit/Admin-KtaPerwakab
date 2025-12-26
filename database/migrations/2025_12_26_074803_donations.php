<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();

            // Relasi campaign
            $table->foreignId('campaign_id')
                ->constrained('donation_campaigns')
                ->cascadeOnDelete();

            // Donatur
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->string('donor_phone')->nullable();
            $table->boolean('is_anonymous')->default(false);

            // Nominal donasi
            $table->decimal('amount', 15, 2);

            // Status donasi (sinkron dengan Tripay)
            $table->enum('status', [
                'PENDING',
                'PAID',
                'FAILED',
                'EXPIRED'
            ])->default('PENDING');

            $table->timestamps();

            $table->index('campaign_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
