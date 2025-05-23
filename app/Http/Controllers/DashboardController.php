<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    public function index(Request $request){
        $queryOrders = $this->getOrdersCounts();
        $topsProducts = $this->getTopsProducts();
        $year = $request->input('year', date('Y'));
        $monthlySales = $this->getMonthlySales($year);

        return Inertia::render('Dashboard', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'counts' => $queryOrders,
            'topsProducts' => $topsProducts,
            'monthlySales' => $monthlySales,
            'selectedYear' => $year
        ]);
    }

    private function getOrdersCounts(){

        $completeOrders = Order::where('status', 'completada')
        ->count();

        $createOrders = Order::where('status', 'creada')
        ->count();

        $pendingOrders = Order:: where('status', 'pendiente')
        ->count();

        $canceledOrder = Order::where('status', 'cancelada')
        ->count();

        $counts = [
            'complete' => $completeOrders,
            'create' => $createOrders,
            'pending' => $pendingOrders,
            'cancel' => $canceledOrder,
        ];
        return $counts;
    }

    private function getTopsProducts(){

        $query = Product::select('products.id', 'products.name')
            ->join('order_product', 'products.id', '=', 'order_product.product_id')
            ->join('orders', 'orders.id', '=', 'order_product.order_id')
            ->where('orders.status', 'completada')
            ->groupBy('products.id', 'products.name')
            ->selectRaw('SUM(order_product.quantity) as total_vendido')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();
        return $query;
    }

    private function getMonthlySales($year)
    {
        $sales = Order::where('status', 'completada')
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, SUM(total) as total_sales')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Formatear los datos para el gráfico
        $months = [];
        $totals = [];

        /* $mes = date('F', mktime(0, 0, 0, 1, 1)); */
        
        for ($i = 1; $i <= 12; $i++) {
            $monthData = $sales->firstWhere('month', $i);
            $months[] = date('F', mktime(0, 0, 0, $i, 1));
            $totals[] = $monthData ? $monthData->total_sales : 0;
        }

        return [
            'months' => $months,
            'totals' => $totals
        ];
    }
}