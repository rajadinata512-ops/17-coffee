<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('addresses', 'recipient_name')) {
                $table->string('recipient_name')->nullable();
            }

            if (!Schema::hasColumn('addresses', 'phone')) {
                $table->string('phone')->nullable();
            }

            if (!Schema::hasColumn('addresses', 'address')) {
                $table->text('address')->nullable();
            }

            if (!Schema::hasColumn('addresses', 'city')) {
                $table->string('city')->nullable();
            }

            if (!Schema::hasColumn('addresses', 'province')) {
                $table->string('province')->nullable();
            }

            if (!Schema::hasColumn('addresses', 'postal_code')) {
                $table->string('postal_code')->nullable();
            }

            if (!Schema::hasColumn('addresses', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (!Schema::hasColumn('addresses', 'is_default')) {
                $table->boolean('is_default')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $columns = [
                'recipient_name',
                'phone',
                'address',
                'city',
                'province',
                'postal_code',
                'notes',
                'is_default',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('addresses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};