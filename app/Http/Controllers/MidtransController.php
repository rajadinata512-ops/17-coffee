<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function notification(Request $request)
    {
        $serverKey = (string) config('services.midtrans.server_key');

        if (!$serverKey) {
            Log::error('Midtrans notification ditolak: MIDTRANS_SERVER_KEY kosong.');
            return response()->json(['message' => 'Server key not configured'], 500);
        }

        $payload = $request->all();

        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if (!$signatureKey || !hash_equals($expectedSignature, $signatureKey)) {
            Log::warning('Midtrans notification signature tidak valid.', $payload);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::with('payment')
            ->where('invoice_number', $orderId)
            ->first();

        if (!$order) {
            Log::warning('Midtrans notification order tidak ditemukan: ' . $orderId, $payload);
            return response()->json(['message' => 'Order not found'], 404);
        }

        $transactionStatus = (string) ($payload['transaction_status'] ?? '');
        $fraudStatus = (string) ($payload['fraud_status'] ?? '');
        $paymentType = (string) ($payload['payment_type'] ?? '');
        $transactionId = (string) ($payload['transaction_id'] ?? '');

        $isPaid = false;
        $isFailed = false;

        if ($transactionStatus === 'capture') {
            $isPaid = $fraudStatus === 'accept';
            $isFailed = in_array($fraudStatus, ['deny'], true);
        } elseif ($transactionStatus === 'settlement') {
            $isPaid = true;
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'], true)) {
            $isFailed = true;
        }

        if ($isPaid) {
            $order->payment_status = 'paid';

            if ($order->order_status === 'pending') {
                $order->order_status = 'processing';
            }

            $order->save();

            if ($order->payment) {
                $order->payment->update([
                    'status' => 'paid',
                    'transaction_id' => $transactionId ?: $order->payment->transaction_id,
                    'payment_type' => $paymentType ?: $order->payment->payment_type,
                    'paid_at' => now(),
                    'raw_response' => $payload,
                ]);
            }
        } elseif ($isFailed) {
            $order->payment_status = 'failed';
            $order->save();

            if ($order->payment) {
                $order->payment->update([
                    'status' => 'failed',
                    'transaction_id' => $transactionId ?: $order->payment->transaction_id,
                    'payment_type' => $paymentType ?: $order->payment->payment_type,
                    'raw_response' => $payload,
                ]);
            }
        } else {
            $order->payment_status = 'unpaid';
            $order->save();

            if ($order->payment) {
                $order->payment->update([
                    'status' => 'pending',
                    'transaction_id' => $transactionId ?: $order->payment->transaction_id,
                    'payment_type' => $paymentType ?: $order->payment->payment_type,
                    'raw_response' => $payload,
                ]);
            }
        }

        return response()->json(['message' => 'OK']);
    }

    public function finish(Request $request)
    {
        $order = $this->findOrderFromRequest($request);

        if ($order && auth()->check() && (int) $order->user_id === (int) auth()->id()) {
            return redirect()->route('orders.show', $order)
                ->with('success', 'Pembayaran sedang diproses. Status akan otomatis berubah setelah Midtrans mengirim notifikasi.');
        }

        return redirect('/orders')->with('success', 'Pembayaran sedang diproses.');
    }

    public function unfinish(Request $request)
    {
        $order = $this->findOrderFromRequest($request);

        if ($order && auth()->check() && (int) $order->user_id === (int) auth()->id()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Pembayaran belum selesai. Kamu masih bisa klik Bayar Sekarang.');
        }

        return redirect('/orders')->with('error', 'Pembayaran belum selesai.');
    }

    public function error(Request $request)
    {
        $order = $this->findOrderFromRequest($request);

        if ($order && auth()->check() && (int) $order->user_id === (int) auth()->id()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Pembayaran gagal atau dibatalkan.');
        }

        return redirect('/orders')->with('error', 'Pembayaran gagal atau dibatalkan.');
    }

    private function findOrderFromRequest(Request $request): ?Order
    {
        $orderId = $request->query('order_id') ?? $request->input('order_id');

        if (!$orderId) {
            return null;
        }

        return Order::where('invoice_number', $orderId)->first();
    }
}