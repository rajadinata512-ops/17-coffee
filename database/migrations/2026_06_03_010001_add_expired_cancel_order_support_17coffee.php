<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('orders')) {
            try {
                if (DB::connection()->getDriverName() === 'mysql') {
                    if (Schema::hasColumn('orders', 'order_status')) {
                        DB::statement("ALTER TABLE orders MODIFY order_status ENUM('pending','processing','shipped','completed','cancelled','expired') NOT NULL DEFAULT 'pending'");
                    }

                    if (Schema::hasColumn('orders', 'status')) {
                        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','processing','shipped','completed','cancelled','expired') NOT NULL DEFAULT 'pending'");
                    }

                    if (Schema::hasColumn('orders', 'payment_status')) {
                        DB::statement("ALTER TABLE orders MODIFY payment_status ENUM('unpaid','paid','failed') NOT NULL DEFAULT 'unpaid'");
                    }
                }
            } catch (Throwable $e) {
                //
            }
        }

        if (Schema::hasTable('payments')) {
            try {
                if (DB::connection()->getDriverName() === 'mysql' && Schema::hasColumn('payments', 'status')) {
                    DB::statement("ALTER TABLE payments MODIFY status ENUM('pending','unpaid','paid','failed','expired','cancelled') NOT NULL DEFAULT 'pending'");
                }

                DB::table('payments')->where('status', 'success')->update(['status' => 'paid']);
            } catch (Throwable $e) {
                //
            }
        }
    }

    public function down(): void
    {
        //
    }
};