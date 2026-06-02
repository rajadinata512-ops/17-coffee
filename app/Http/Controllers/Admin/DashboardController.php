<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ─── Stats ────────────────────────────────────────
        $totalOrders     = Order::count();
        $totalRevenue    = Order::where('payment_status', 'paid')->sum('total_price') ?? 0;
        $totalProducts   = Product::count();
        $totalUsers      = User::where('role', 'user')->count();
        $pendingOrders   = Order::where('order_status', 'pending')->count();
        $lowStockProducts = Product::where('stock', '<=', 5)->where('status', 'active')->count();

        // ─── Order chart: last 7 days ─────────────────────
        $rawChart = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN payment_status = 'paid' THEN total_price ELSE 0 END) as revenue")
            )
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartDates   = [];
        $chartOrders  = [];
        $chartRevenue = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartDates[]   = Carbon::now()->subDays($i)->locale('id')->isoFormat('D MMM');
            $chartOrders[]  = $rawChart->has($date) ? (int) $rawChart[$date]->total   : 0;
            $chartRevenue[] = $rawChart->has($date) ? (int) $rawChart[$date]->revenue : 0;
        }

        // ─── Top 5 products by quantity sold ──────────────
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // ─── Recent orders ─────────────────────────────────
        $recentOrders = Order::with(['user'])
            ->latest()
            ->limit(8)
            ->get();

        // ─── Low stock list ────────────────────────────────
        $lowStockList = Product::with('category')
            ->where('stock', '<=', 5)
            ->where('status', 'active')
            ->orderBy('stock')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders', 'totalRevenue', 'totalProducts', 'totalUsers',
            'pendingOrders', 'lowStockProducts',
            'chartDates', 'chartOrders', 'chartRevenue',
            'topProducts', 'recentOrders', 'lowStockList'
        ));
    }
}
