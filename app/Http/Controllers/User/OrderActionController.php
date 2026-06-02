<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderActionController extends Controller
{
    public function cancel(Order $order): RedirectResponse
    {
        $order = Order::where('id', $order->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $statusColumn = Schema::hasColumn('orders', 'order_status')
            ? 'order_status'
            : (Schema::hasColumn('orders', 'status') ? 'status' : null);

        $orderStatus = strtolower((string) ($statusColumn ? $order->{$statusColumn} : 'pending'));
        $paymentStatus = strtolower((string) ($order->payment_status ?? 'unpaid'));

        if ($paymentStatus === 'paid') {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan karena pembayaran sudah berhasil.');
        }

        if (in_array($orderStatus, ['processing', 'shipped', 'completed', 'cancelled', 'expired'], true)) {
            return back()->with('error', 'Pesanan ini tidak bisa dibatalkan karena statusnya sudah berubah.');
        }

        DB::transaction(function () use ($order, $statusColumn) {
            $this->restoreStock((int) $order->id);

            if ($statusColumn) {
                try {
                    $order->{$statusColumn} = 'cancelled';
                } catch (\Throwable $e) {
                    //
                }
            }

            if (Schema::hasColumn('orders', 'payment_status')) {
                $order->payment_status = 'failed';
            }

            $order->save();

            if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'order_id') && Schema::hasColumn('payments', 'status')) {
                DB::table('payments')
                    ->where('order_id', $order->id)
                    ->whereIn('status', ['pending', 'unpaid'])
                    ->update([
                        'status' => 'cancelled',
                        'updated_at' => now(),
                    ]);
            }
        });

        return redirect()
            ->route('orders.index')
            ->with('success', 'Pesanan berhasil dibatalkan. Jika stok sempat berkurang, stok sudah dikembalikan.');
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