<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->string('payment_code')->nullable()->unique();
                $table->string('payment_gateway')->default('manual');
                $table->string('payment_method')->nullable();
                $table->unsignedBigInteger('amount')->default(0);
                $table->enum('status', ['pending', 'unpaid', 'paid', 'failed', 'expired', 'cancelled'])->default('pending');
                $table->string('transaction_id')->nullable();
                $table->string('snap_token')->nullable();
                $table->string('snap_redirect_url')->nullable();
                $table->json('raw_response')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });

            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'order_id')) {
                $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete();
            }

            if (!Schema::hasColumn('payments', 'payment_code')) {
                $table->string('payment_code')->nullable()->unique();
            }

            if (!Schema::hasColumn('payments', 'payment_gateway')) {
                $table->string('payment_gateway')->default('manual');
            }

            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }

            if (!Schema::hasColumn('payments', 'amount')) {
                $table->unsignedBigInteger('amount')->default(0);
            }

            if (!Schema::hasColumn('payments', 'status')) {
                $table->string('status')->default('pending');
            }

            if (!Schema::hasColumn('payments', 'transaction_id')) {
                $table->string('transaction_id')->nullable();
            }

            if (!Schema::hasColumn('payments', 'snap_token')) {
                $table->string('snap_token')->nullable();
            }

            if (!Schema::hasColumn('payments', 'snap_redirect_url')) {
                $table->string('snap_redirect_url')->nullable();
            }

            if (!Schema::hasColumn('payments', 'raw_response')) {
                $table->json('raw_response')->nullable();
            }

            if (!Schema::hasColumn('payments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};