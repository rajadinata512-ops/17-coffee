<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_admin')) {
                $table->boolean('is_admin')->default(false)->after('role');
            }

            if (!Schema::hasColumn('users', 'email_otp_hash')) {
                $table->string('email_otp_hash')->nullable()->after('email_verified_at');
            }

            if (!Schema::hasColumn('users', 'email_otp_expires_at')) {
                $table->timestamp('email_otp_expires_at')->nullable()->after('email_otp_hash');
            }

            if (!Schema::hasColumn('users', 'email_otp_attempts')) {
                $table->unsignedTinyInteger('email_otp_attempts')->default(0)->after('email_otp_expires_at');
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status');
            }
        });

        Schema::table('addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('addresses', 'receiver_name')) {
                $table->string('receiver_name')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('addresses', 'full_address')) {
                $table->text('full_address')->nullable()->after('address');
            }
        });

        if (Schema::hasTable('users')) {
            DB::table('users')
                ->whereRaw('LOWER(email) = ?', ['seventeencoffeee@gmail.com'])
                ->update([
                    'role' => 'admin',
                    'is_admin' => true,
                ]);

            DB::table('users')
                ->whereRaw('LOWER(email) != ?', ['seventeencoffeee@gmail.com'])
                ->update([
                    'role' => 'user',
                    'is_admin' => false,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            foreach (['receiver_name', 'full_address'] as $column) {
                if (Schema::hasColumn('addresses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'image')) {
                $table->dropColumn('image');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            foreach (['is_admin', 'email_otp_hash', 'email_otp_expires_at', 'email_otp_attempts'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};