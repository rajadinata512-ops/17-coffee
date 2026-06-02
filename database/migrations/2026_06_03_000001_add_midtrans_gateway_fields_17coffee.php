<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (!Schema::hasColumn('payments', 'snap_token')) {
                    $table->string('snap_token')->nullable()->after('payment_gateway');
                }

                if (!Schema::hasColumn('payments', 'redirect_url')) {
                    $table->text('redirect_url')->nullable()->after('snap_token');
                }

                if (!Schema::hasColumn('payments', 'transaction_id')) {
                    $table->string('transaction_id')->nullable()->after('redirect_url');
                }

                if (!Schema::hasColumn('payments', 'payment_type')) {
                    $table->string('payment_type')->nullable()->after('transaction_id');
                }

                if (!Schema::hasColumn('payments', 'raw_response')) {
                    $table->json('raw_response')->nullable()->after('payment_type');
                }
            });
        }

        if (Schema::hasTable('orders')) {
            $driver = DB::connection()->getDriverName();

            if ($driver === 'mysql') {
                DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('bank_transfer','qris','ewallet','credit_card','cod') NOT NULL");
            }
        }

        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'payment_status')) {
            try {
                DB::table('orders')->where('payment_status', 'pending')->update(['payment_status' => 'unpaid']);
            } catch (Throwable $e) {
                //
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                foreach (['snap_token', 'redirect_url', 'transaction_id', 'payment_type', 'raw_response'] as $column) {
                    if (Schema::hasColumn('payments', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};