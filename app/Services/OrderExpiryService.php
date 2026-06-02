<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderExpiryService
{
    public function expire(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        if (!Schema::hasColumn('orders', 'created_at')) {
            return;
        }

        $statusColumn = Schema::hasColumn('orders', 'order_status')
            ? 'order_status'
            : (Schema::hasColumn('orders', 'status') ? 'status' : null);

        if (!$statusColumn || !Schema::hasColumn('orders', 'payment_status')) {
            return;
        }

        $query = DB::table('orders')
            ->where($statusColumn, 'pending')
            ->where('payment_status', 'unpaid')
            ->where('created_at', '<=', now()->subHours(24));

        if (Schema::hasColumn('orders', 'payment_method')) {
            $query->where(function ($subQuery) {
                $subQuery->whereNull('payment_method')
                    ->orWhere('payment_method', '!=', 'cod');
            });
        }

        if (Schema::hasColumn('orders', 'payment_gateway')) {
            $query->where(function ($subQuery) {
                $subQuery->whereNull('payment_gateway')
                    ->orWhere('payment_gateway', '!=', 'cod');
            });
        }

        $expiredOrders = $query->pluck('id');

        if ($expiredOrders->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($expiredOrders, $statusColumn) {
            foreach ($expiredOrders as $orderId) {
                $this->restoreStock((int) $orderId);
            }

            try {
                DB::table('orders')
                    ->whereIn('id', $expiredOrders)
                    ->update([
                        $statusColumn => 'expired',
                        'payment_status' => 'failed',
                        'updated_at' => now(),
                    ]);
            } catch (\Throwable $e) {
                DB::table('orders')
                    ->whereIn('id', $expiredOrders)
                    ->update([
                        $statusColumn => 'cancelled',
                        'payment_status' => 'failed',
                        'updated_at' => now(),
                    ]);
            }

            if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'order_id') && Schema::hasColumn('payments', 'status')) {
                DB::table('payments')
                    ->whereIn('order_id', $expiredOrders)
                    ->whereIn('status', ['pending', 'unpaid'])
                    ->update([
                        'status' => 'expired',
                        'updated_at' => now(),
                    ]);
            }
        });
    }

    private function restoreStock(int $orderId): void
    {
        if (!Schema::hasTable('order_items') || !Schema::hasTable('products')) {
            return;
        }

        if (!Schema::hasColumn('order_items', 'order_id') || !Schema::hasColumn('order_items', 'product_id')) {
            return;
        }

        if (!Schema::hasColumn('products', 'stock')) {
            return;
        }

        $quantityColumn = Schema::hasColumn('order_items', 'quantity')
            ? 'quantity'
            : (Schema::hasColumn('order_items', 'qty') ? 'qty' : null);

        if (!$quantityColumn) {
            return;
        }

        $items = DB::table('order_items')
            ->where('order_id', $orderId)
            ->get(['product_id', $quantityColumn]);

        foreach ($items as $item) {
            DB::table('products')
                ->where('id', $item->product_id)
                ->increment('stock', (int) $item->{$quantityColumn});
        }
    }
}