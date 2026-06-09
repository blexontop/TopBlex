<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

// Panel de administración: calcula las estadísticas del negocio (ventas, stock, etc.).
class DashboardController extends Controller
{
    // Reúne los números clave y los manda a la vista del dashboard.
    public function index()
    {
        try {
            $totalProducts = Product::count();         
            $totalStock = (int) Product::sum('stock');  

            // Productos con poco stock (5 o menos) para avisar de reposición.
            $lowStockProducts = Product::query()
                ->where('stock', '<=', 5)
                ->orderBy('stock')
                ->orderBy('name')
                ->limit(6)
                ->get(['id', 'name', 'stock']);

            $totalOrders = Order::count();              // nº total de pedidos

            // Ingresos totales (sin contar pedidos cancelados ni reembolsados).
            $totalRevenue = (float) Order::query()
                ->whereNotIn('status', ['cancelled', 'refunded'])
                ->sum('total');

            // Ingresos solo del mes actual.
            $monthlyRevenue = (float) Order::query()
                ->whereNotIn('status', ['cancelled', 'refunded'])
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('total');

            // Los 5 productos más vendidos (sumando las cantidades vendidas).
            $topProducts = OrderItem::query()
                ->select('product_name', DB::raw('SUM(quantity) as total_sold'))
                ->groupBy('product_name')
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get();
        } catch (QueryException) {
            // Si las tablas aún no existen, muestra el panel con valores a cero.
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
