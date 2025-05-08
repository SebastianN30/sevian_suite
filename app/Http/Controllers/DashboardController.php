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

        return Inertia::render('Dashboard', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'counts' => $queryOrders,
            'topsProducts' => $topsProducts
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
}