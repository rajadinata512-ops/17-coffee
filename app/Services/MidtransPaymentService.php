<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransPaymentService
{
    public function __construct()
    {
        Config::$serverKey = (string) config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createSnapTransaction(Order $order): array
    {
        if (!config('services.midtrans.server_key')) {
            throw new \RuntimeException('MIDTRANS_SERVER_KEY belum diisi di .env');
        }

        $order->loadMissing(['user', 'orderItems.product', 'address']);

        $itemDetails = $order->orderItems->map(function ($item) {
            $qty = (int) ($item->quantity ?? $item->qty ?? 1);
            $price = (int) round((float) ($item->price ?? $item->unit_price ?? 0));

            return [
                'id' => (string) ($item->product_id ?? $item->id),
                'price' => $price,
                'quantity' => $qty,
                'name' => mb_substr((string) ($item->product->name ?? $item->product_name ?? 'Produk 17 Coffee'), 0, 50),
            ];
        })->values()->all();

        if ((int) $order->shipping_cost > 0) {
            $itemDetails[] = [
                'id' => 'ONGKIR',
                'price' => (int) round((float) $order->shipping_cost),
                'quantity' => 1,
                'name' => 'Ongkir',
            ];
        }

        $enabledPayments = $this->enabledPayments($order->payment_method);

        $params = [
            'transaction_details' => [
                'order_id' => $order->invoice_number,
                'gross_amount' => (int) round((float) $order->total_price),
            ],
            'item_details' => $itemDetails,
            'enabled_payments' => $enabledPayments,
            'customer_details' => [
                'first_name' => $order->address->recipient_name
                    ?? $order->address->receiver_name
                    ?? $order->user->name
                    ?? 'Customer',
                'email' => $order->user->email ?? null,
                'phone' => $order->address->phone ?? null,
                'billing_address' => [
                    'first_name' => $order->address->recipient_name
                        ?? $order->address->receiver_name
                        ?? $order->user->name
                        ?? 'Customer',
                    'phone' => $order->address->phone ?? null,
                    'address' => $order->address->address ?? $order->address->full_address ?? null,
                    'city' => $order->address->city ?? null,
                    'postal_code' => $order->address->postal_code ?? null,
                    'country_code' => 'IDN',
                ],
            ],
            'callbacks' => [
                'finish' => route('midtrans.finish', [], true),
                'unfinish' => route('midtrans.unfinish', [], true),
                'error' => route('midtrans.error', [], true),
            ],
            'expiry' => [
                'unit' => 'hour',
                'duration' => 24,
            ],
        ];

        $response = Snap::createTransaction($params);

        return [
            'token' => $response->token ?? null,
            'redirect_url' => $response->redirect_url ?? null,
            'raw' => json_decode(json_encode($response), true),
        ];
    }

    private function enabledPayments(?string $method): array
    {
        return match ($method) {
            'bank_transfer' => ['bca_va', 'bni_va', 'bri_va', 'permata_va', 'other_va', 'echannel'],
            'qris' => ['gopay'],
            default => ['gopay', 'bca_va', 'bni_va', 'bri_va', 'permata_va', 'other_va', 'echannel'],
        };
    }
}