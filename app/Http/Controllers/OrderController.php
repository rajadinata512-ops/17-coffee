<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderItems.product', 'address', 'payment', 'ratings'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    public function checkout()
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        $cart->load('cartItems.product.category');

        $subtotal = $cart->cartItems->sum(fn ($item) => $item->price * $item->quantity);
        $shippingCost = $cart->cartItems->isEmpty() ? 0 : 8000;
        $total = $subtotal + $shippingCost;

        return view('user.orders.checkout', compact('cart', 'subtotal', 'shippingCost', 'total'));
    }

    public function store(Request $request, MidtransPaymentService $midtrans)
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with('cartItems.product')
            ->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('checkout')->with('error', 'Keranjang kamu masih kosong. Pilih menu dulu sebelum checkout.');
        }

        $data = $request->validate([
            'recipient_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:1000'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'in:bank_transfer,qris,cod'],
        ]);

        $order = DB::transaction(function () use ($data) {
            $cart = Cart::where('user_id', auth()->id())
                ->with('cartItems.product')
                ->lockForUpdate()
                ->firstOrFail();

            foreach ($cart->cartItems as $item) {
                if (!$item->product || $item->product->stock < $item->quantity || $item->product->status !== 'active') {
                    abort(422, 'Stok produk tidak cukup atau produk tidak aktif.');
                }
            }

            $fullAddressText = $data['address'] . ', ' . $data['city'] . ', ' . $data['province'];

            $addressData = ['user_id' => auth()->id()];

            if (Schema::hasColumn('addresses', 'receiver_name')) {
                $addressData['receiver_name'] = $data['recipient_name'];
            }

            if (Schema::hasColumn('addresses', 'recipient_name')) {
                $addressData['recipient_name'] = $data['recipient_name'];
            }

            if (Schema::hasColumn('addresses', 'phone')) {
                $addressData['phone'] = $data['phone'];
            }

            if (Schema::hasColumn('addresses', 'address')) {
                $addressData['address'] = $data['address'];
            }

            if (Schema::hasColumn('addresses', 'full_address')) {
                $addressData['full_address'] = $fullAddressText;
            }

            if (Schema::hasColumn('addresses', 'city')) {
                $addressData['city'] = $data['city'];
            }

            if (Schema::hasColumn('addresses', 'province')) {
                $addressData['province'] = $data['province'];
            }

            if (Schema::hasColumn('addresses', 'postal_code')) {
                $addressData['postal_code'] = $data['postal_code'] ?? null;
            }

            if (Schema::hasColumn('addresses', 'notes')) {
                $addressData['notes'] = $data['notes'] ?? null;
            }

            if (Schema::hasColumn('addresses', 'is_default')) {
                $addressData['is_default'] = false;
            }

            $address = Address::create($addressData);

            $subtotal = $cart->cartItems->sum(fn ($item) => $item->price * $item->quantity);
            $shippingCost = 8000;
            $total = $subtotal + $shippingCost;

            $order = Order::create([
                'user_id' => auth()->id(),
                'address_id' => $address->id,
                'invoice_number' => 'INV-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4)),
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_price' => $total,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
            ]);

            foreach ($cart->cartItems as $item) {
                $this->insertOrderItem($order->id, $item);
                $item->product->decrement('stock', $item->quantity);
            }

            Payment::create([
                'order_id' => $order->id,
                'payment_code' => 'PAY-' . strtoupper(Str::random(10)),
                'payment_gateway' => $data['payment_method'] === 'cod' ? 'COD' : 'Midtrans',
                'amount' => $total,
                'status' => 'pending',
            ]);

            $cart->cartItems()->delete();

            return $order->load(['orderItems.product', 'address', 'payment', 'user']);
        });

        if ($order->payment_method !== 'cod') {
            try {
                $snap = $midtrans->createSnapTransaction($order);

                $update = [
                    'payment_gateway' => 'Midtrans',
                    'snap_token' => $snap['token'] ?? null,
                    'redirect_url' => $snap['redirect_url'] ?? null,
                    'raw_response' => $snap['raw'] ?? null,
                ];

                $order->payment?->update($update);

                if (!empty($snap['redirect_url'])) {
                    return redirect()->away($snap['redirect_url']);
                }

                return redirect()->route('orders.show', $order)
                    ->with('error', 'Link pembayaran Midtrans gagal dibuat. Cek konfigurasi Midtrans.');
            } catch (\Throwable $e) {
                report($e);

                return redirect()->route('orders.show', $order)
                    ->with('error', 'Pesanan berhasil dibuat, tapi gateway pembayaran belum aktif/konfigurasi Midtrans belum benar. Cek MIDTRANS_SERVER_KEY di .env.');
            }
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan COD berhasil dibuat. Kirim detail pesanan ke WhatsApp admin.');
    }

    private function insertOrderItem(int $orderId, $cartItem): void
    {
        $product = $cartItem->product;
        $quantity = (int) $cartItem->quantity;
        $price = (float) ($cartItem->price ?? $product->price ?? 0);
        $subtotal = $price * $quantity;

        $row = [];

        if (Schema::hasColumn('order_items', 'order_id')) {
            $row['order_id'] = $orderId;
        }

        if (Schema::hasColumn('order_items', 'product_id')) {
            $row['product_id'] = $cartItem->product_id;
        }

        if (Schema::hasColumn('order_items', 'quantity')) {
            $row['quantity'] = $quantity;
        }

        if (Schema::hasColumn('order_items', 'qty')) {
            $row['qty'] = $quantity;
        }

        if (Schema::hasColumn('order_items', 'price')) {
            $row['price'] = $price;
        }

        if (Schema::hasColumn('order_items', 'unit_price')) {
            $row['unit_price'] = $price;
        }

        if (Schema::hasColumn('order_items', 'subtotal')) {
            $row['subtotal'] = $subtotal;
        }

        if (Schema::hasColumn('order_items', 'total')) {
            $row['total'] = $subtotal;
        }

        if (Schema::hasColumn('order_items', 'total_price')) {
            $row['total_price'] = $subtotal;
        }

        if (Schema::hasColumn('order_items', 'product_name')) {
            $row['product_name'] = $product->name ?? 'Produk';
        }

        if (Schema::hasColumn('order_items', 'name')) {
            $row['name'] = $product->name ?? 'Produk';
        }

        if (Schema::hasColumn('order_items', 'created_at')) {
            $row['created_at'] = now();
        }

        if (Schema::hasColumn('order_items', 'updated_at')) {
            $row['updated_at'] = now();
        }

        DB::table('order_items')->insert($row);
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load(['orderItems.product', 'address', 'payment', 'ratings']);

        $whatsappUrl = $this->buildWhatsappUrl($order);

        return view('user.orders.show', compact('order', 'whatsappUrl'));
    }

    private function buildWhatsappUrl(Order $order): string
    {
        $adminPhone = preg_replace('/\D+/', '', env('ADMIN_WHATSAPP', '6281234567890'));

        $order->loadMissing(['orderItems.product', 'address']);

        $items = $order->orderItems->map(function ($item) {
            $productName = $item->product->name ?? $item->product_name ?? $item->name ?? 'Produk';
            $quantity = $item->quantity ?? $item->qty ?? 1;
            $price = $item->price ?? $item->unit_price ?? 0;
            $subtotal = $price * $quantity;

            return "- {$productName} x{$quantity} = Rp " . number_format($subtotal, 0, ',', '.');
        })->implode("\n");

        $paymentLabel = match ($order->payment_method) {
            'bank_transfer' => 'Midtrans Bank Transfer / Virtual Account',
            'qris' => 'Midtrans QRIS',
            'cod' => 'COD / Bayar di Tempat',
            default => $order->payment_method,
        };

        $paymentNote = '';
        if ($order->payment_method !== 'cod' && $order->payment?->redirect_url) {
            $paymentNote = "\nLink Bayar: " . $order->payment->redirect_url . "\n";
        }

        $address = $order->address;
        $recipientName = $address->recipient_name ?? $address->receiver_name ?? auth()->user()->name ?? '-';
        $phone = $address->phone ?? '-';

        $fullAddress = $address->full_address
            ?? (($address->address ?? '-') . ', ' . ($address->city ?? '-') . ', ' . ($address->province ?? '-'));

        $postalCode = $address->postal_code ?? '-';
        $notes = $address->notes ?? '-';

        $message = "Halo Admin 17 Coffee, ada pesanan baru.\n\n" .
            "Invoice: {$order->invoice_number}\n" .
            "Nama: {$recipientName}\n" .
            "No HP: {$phone}\n" .
            "Alamat: {$fullAddress}\n" .
            "Kode Pos: {$postalCode}\n" .
            "Catatan: {$notes}\n\n" .
            "Pesanan:\n{$items}\n\n" .
            "Subtotal: Rp " . number_format($order->subtotal, 0, ',', '.') . "\n" .
            "Ongkir: Rp " . number_format($order->shipping_cost, 0, ',', '.') . "\n" .
            "Total: Rp " . number_format($order->total_price, 0, ',', '.') . "\n" .
            "Metode Pembayaran: {$paymentLabel}\n" .
            "Status Pembayaran: {$order->payment_status}\n" .
            $paymentNote . "\n" .
            "Mohon diproses dan diantar ya.";

        return "https://wa.me/{$adminPhone}?text=" . rawurlencode($message);
    }
}