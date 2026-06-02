<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class RatingController extends Controller
{
    public function store(Request $request, Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $orderStatus = strtolower($order->order_status ?? $order->status ?? '');

        if (!in_array($orderStatus, ['completed', 'selesai', 'delivered', 'terkirim'])) {
            return back()->with('error', 'Rating hanya bisa diberikan setelah pesanan selesai.');
        }

        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:1000'],
        ]);

        $order->load('orderItems');

        $productInOrder = $order->orderItems
            ->where('product_id', $data['product_id'])
            ->count() > 0;

        if (!$productInOrder) {
            return back()->with('error', 'Produk ini tidak ada di pesanan kamu.');
        }

        $query = Rating::query();

        if (Schema::hasColumn('ratings', 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        if (Schema::hasColumn('ratings', 'order_id')) {
            $query->where('order_id', $order->id);
        }

        if (Schema::hasColumn('ratings', 'product_id')) {
            $query->where('product_id', $data['product_id']);
        }

        $rating = $query->first() ?? new Rating();

        if (Schema::hasColumn('ratings', 'user_id')) {
            $rating->user_id = auth()->id();
        }

        if (Schema::hasColumn('ratings', 'order_id')) {
            $rating->order_id = $order->id;
        }

        if (Schema::hasColumn('ratings', 'product_id')) {
            $rating->product_id = $data['product_id'];
        }

        if (Schema::hasColumn('ratings', 'rating')) {
            $rating->rating = $data['rating'];
        }

        if (Schema::hasColumn('ratings', 'review')) {
            $rating->review = $data['review'] ?? null;
        }

        if (Schema::hasColumn('ratings', 'comment')) {
            $rating->comment = $data['review'] ?? null;
        }

        $rating->save();

        return redirect()
            ->route('orders.index')
            ->with('success', 'Rating berhasil disimpan. Pesanan yang sudah diberi rating masuk ke Riwayat Pesanan.');
    }
}