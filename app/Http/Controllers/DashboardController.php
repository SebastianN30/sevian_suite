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

        $queryOrders = $this->getCounts();

        return Inertia::render('Dashboard', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'counts' => $queryOrders
        ]);
    }

    private function getCounts(){

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
}