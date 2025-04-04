<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->with('products')
            ->latest()
            ->paginate(8);
        return Inertia::render('Orders/List', [
            'orders' => $orders
        ]);
    }

    public function edit(Order $order)
    {
        $order->load('products', 'client');
        $products = Product::where('status', Product::STATUS_ACTIVE)->get();
        return Inertia::render('Orders/Edit', [
            'order' => $order,
            'client' => $order->client,
            'products' => $products
        ]);
    }
}
