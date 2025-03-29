<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){
        $orders = Order::query()
            ->with('products')
            ->latest()
            ->paginate(8);
        return Inertia::render('Orders/List', [
            'orders' => $orders
        ]);
    }

    public function create(){
        return Inertia::render('Orders/New', [

        ]);
    }

    public function edit(){
        return Inertia::render('Orders/Edit', [

        ]);
    }
}
