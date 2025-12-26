<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tripay_transactions', function (Blueprint $table) {
            $table->id();

            /**
             * Identitas Internal
             */
            $table->string('merchant_ref')->unique();
            // reference internal sistem (order_id, donation_id, invoice, dll)

            /**
             * Identitas Tripay
             */
            $table->string('tripay_reference')->nullable()->unique();
            $table->string('payment_method')->nullable();
            $table->string('payment_name')->nullable();

            /**
             * Transaksi
             */
            $table->decimal('amount', 15, 2);
            $table->decimal('fee_customer', 15, 2)->default(0);
            $table->decimal('fee_merchant', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->nullable(); // amount + fee

            /**
             * Status Tripay
             */
            $table->enum('status', [
                'UNPAID',
                'PAID',
                'EXPIRED',
                'FAILED',
                'REFUND'
            ])->default('UNPAID');

            /**
             * Tipe Transaksi (GLOBAL)
             * contoh: donation, order, membership, event, topup
             */
            $table->string('transaction_type');

            /**
             * Relasi fleksibel (tanpa FK keras)
             * contoh:
             * - donation_id
             * - order_id
             * - invoice_id
             */
            $table->unsignedBigInteger('related_id')->nullable();

            /**
             * Customer / User (opsional)
             */
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();

            /**
             * Callback & Audit
             */
            $table->json('tripay_payload')->nullable(); // full webhook response
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            /**
             * Environment
             */
            $table->boolean('is_dev')->default(true); // true = sandbox, false = prod

            $table->timestamps();

            // Index penting
            $table->index(['transaction_type', 'related_id']);
            $table->index('status');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tripay_transactions');
    }
};
