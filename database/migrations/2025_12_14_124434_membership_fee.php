<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('membership_fees', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 12, 2);

            $table->enum('type', ['bulanan', 'tahunan'])->default('tahunan');

            $table->enum('payment_method', ['tripay', 'manual'])->default('manual');

            $table->enum('payment_status', ['pending', 'success', 'failed'])->default('pending');

            $table->string('proof_image')->nullable();

            $table->unsignedBigInteger('validated_by')->nullable();

            $table->timestamp('payment_date')->nullable();

            $table->timestamps();

            // RELATION
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('validated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_fees');
    }
};
