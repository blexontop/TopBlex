<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $totalProducts = Product::count();
            $totalStock = (int) Product::sum('stock');
            $lowStockProducts = Product::query()
                ->where('stock', '<=', 5)
                ->orderBy('stock')
                ->orderBy('name')
                ->limit(6)
                ->get(['id', 'name', 'stock']);

            $totalOrders = Order::count();
            $totalRevenue = (float) Order::query()
                ->whereNotIn('status', ['cancelled', 'refunded'])
                ->sum('total');

            $monthlyRevenue = (float) Order::query()
                ->whereNotIn('status', ['cancelled', 'refunded'])
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('total');

            $topProducts = OrderItem::query()
                ->select('product_name', DB::raw('SUM(quantity) as total_sold'))
                ->groupBy('product_name')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get();
        } catch (QueryException) {
            $totalProducts = 0;
            $totalStock = 0;
            $lowStockProducts = collect();
            $totalOrders = 0;
            $totalRevenue = 0.0;
            $monthlyRevenue = 0.0;
            $topProducts = collect();
        }

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalStock',
            'lowStockProducts',
            'totalOrders',
            'totalRevenue',
            'monthlyRevenue',
            'topProducts'
        ));
    }
}
