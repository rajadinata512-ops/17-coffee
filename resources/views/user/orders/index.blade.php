@extends('user.layouts.app')

@section('title', 'Pesanan Saya - 17 Coffee')

@section('content')
@php
    $orderList = $orders instanceof \Illuminate\Pagination\AbstractPaginator ? collect($orders->items()) : collect($orders ?? []);

    $isHistory = function ($order) {
        $status = strtolower((string) ($order->order_status ?? $order->status ?? 'pending'));
        $paymentStatus = strtolower((string) ($order->payment_status ?? 'unpaid'));

        return in_array($status, ['completed', 'cancelled', 'expired'], true)
            || in_array($paymentStatus, ['failed'], true);
    };

    $activeOrders = $orderList->reject($isHistory);
    $historyOrders = $orderList->filter($isHistory);

    $badgeClass = function ($status) {
        $status = strtolower((string) $status);

        return match ($status) {
            'completed' => 'badge badge-green',
            'processing' => 'badge badge-blue',
            'cancelled', 'expired' => 'badge badge-red',
            default => 'badge badge-yellow',
        };
    };
@endphp

<style>
    .orders-page {
        padding: 56px 5%;
        background: linear-gradient(180deg, #2a1007 0%, #3a1a0c 100%);
        min-height: calc(100vh - 80px);
        color: #fff;
    }

    .orders-header { margin-bottom: 28px; }
    .orders-label {
        color: #dd8a20;
        letter-spacing: 8px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .orders-title {
        font-size: 42px;
        line-height: 1.1;
        margin: 0;
        color: #fff7ec;
    }

    .section-box {
        background: #fff;
        color: #2a1007;
        border-radius: 24px;
        padding: 28px;
        margin-bottom: 28px;
        border: 1px solid rgba(255, 196, 125, .35);
        box-shadow: 0 18px 45px rgba(0,0,0,.18);
    }

    .section-title {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        align-items: center;
        margin-bottom: 18px;
    }

    .section-title h2 {
        margin: 0;
        font-size: 24px;
    }

    .count-pill {
        background: #fff4d6;
        color: #6b3b0d;
        padding: 8px 14px;
        border-radius: 999px;
        font-weight: 800;
        font-size: 13px;
    }

    .order-card {
        border: 1px solid #f1dcc8;
        border-radius: 20px;
        padding: 18px;
        margin-bottom: 14px;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 18px;
        align-items: center;
    }

    .invoice {
        font-weight: 900;
        font-size: 20px;
        margin-bottom: 7px;
    }

    .meta {
        color: #667085;
        font-size: 14px;
        line-height: 1.7;
    }

    .order-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
        align-items: center;
    }

    .btn-brown,
    .btn-danger,
    .btn-soft {
        border: 0;
        border-radius: 999px;
        padding: 11px 18px;
        font-weight: 900;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn-brown { background: #3b1708; color: #fff; }
    .btn-danger { background: #fee2e2; color: #b42318; }
    .btn-soft { background: #fff7e6; color: #8a4b10; }

    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        text-transform: capitalize;
    }

    .badge-yellow { background: #fff3c4; color: #8a5a00; }
    .badge-green { background: #dcfae6; color: #087443; }
    .badge-blue { background: #dbeafe; color: #1d4ed8; }
    .badge-red { background: #fee2e2; color: #b42318; }

    .empty-box {
        border: 1px dashed #e9c9a8;
        background: #fffaf4;
        color: #8a6a4c;
        border-radius: 18px;
        padding: 24px;
        text-align: center;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .orders-title { font-size: 32px; }
        .order-card { grid-template-columns: 1fr; }
        .order-actions { justify-content: flex-start; }
    }
</style>

<section class="orders-page">
    <div class="orders-header">
        <div class="orders-label">Pesanan</div>
        <h1 class="orders-title">Pesanan Saya</h1>
    </div>

    @if(session('success'))
        <div class="section-box" style="background:#ecfdf3;color:#087443;font-weight:800;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="section-box" style="background:#fff1f3;color:#b42318;font-weight:800;">
            {{ session('error') }}
        </div>
    @endif

    <div class="section-box">
        <div class="section-title">
            <h2>Pesanan Aktif</h2>
            <span class="count-pill">{{ $activeOrders->count() }} pesanan</span>
        </div>

        @forelse($activeOrders as $order)
            @php
                $status = strtolower((string) ($order->order_status ?? $order->status ?? 'pending'));
                $paymentStatus = strtolower((string) ($order->payment_status ?? 'unpaid'));
                $method = strtolower((string) ($order->payment_method ?? $order->payment_gateway ?? ''));
                $canCancel = $paymentStatus !== 'paid' && in_array($status, ['pending'], true);
            @endphp

            <div class="order-card">
                <div>
                    <div class="invoice">#{{ $order->invoice_number ?? ('INV-' . $order->id) }}</div>
                    <div class="meta">
                        Dibuat: {{ optional($order->created_at)->format('d M Y H:i') }}<br>
                        Total: <strong>Rp {{ number_format((float) ($order->total_price ?? $order->total ?? 0), 0, ',', '.') }}</strong><br>
                        Pembayaran: <strong>{{ strtoupper($method ?: '-') }}</strong> · Status bayar: <strong>{{ $paymentStatus }}</strong>
                    </div>
                </div>

                <div class="order-actions">
                    <span class="{{ $badgeClass($status) }}">{{ $status }}</span>
                    <a class="btn-brown" href="{{ route('orders.show', $order) }}">Detail</a>

                    @if($canCancel)
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Batalkan pesanan ini? Pesanan akan masuk riwayat dan stok akan dikembalikan.');">
                            @csrf
                            <button class="btn-danger" type="submit">Batalkan</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-box">Tidak ada pesanan aktif.</div>
        @endforelse
    </div>

    <div class="section-box">
        <div class="section-title">
            <h2>Riwayat Pesanan</h2>
            <span class="count-pill">{{ $historyOrders->count() }} pesanan</span>
        </div>

        @forelse($historyOrders as $order)
            @php
                $status = strtolower((string) ($order->order_status ?? $order->status ?? 'pending'));
                $paymentStatus = strtolower((string) ($order->payment_status ?? 'unpaid'));
                $method = strtolower((string) ($order->payment_method ?? $order->payment_gateway ?? ''));
            @endphp

            <div class="order-card">
                <div>
                    <div class="invoice">#{{ $order->invoice_number ?? ('INV-' . $order->id) }}</div>
                    <div class="meta">
                        Dibuat: {{ optional($order->created_at)->format('d M Y H:i') }}<br>
                        Total: <strong>Rp {{ number_format((float) ($order->total_price ?? $order->total ?? 0), 0, ',', '.') }}</strong><br>
                        Pembayaran: <strong>{{ strtoupper($method ?: '-') }}</strong> · Status bayar: <strong>{{ $paymentStatus }}</strong>
                    </div>
                </div>

                <div class="order-actions">
                    <span class="{{ $badgeClass($status) }}">{{ $status }}</span>
                    <a class="btn-soft" href="{{ route('orders.show', $order) }}">Lihat / Update Rating</a>
                </div>
            </div>
        @empty
            <div class="empty-box">Riwayat pesanan belum ada.</div>
        @endforelse
    </div>
</section>
@endsection