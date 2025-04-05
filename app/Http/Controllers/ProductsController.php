<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
            $search = $request->input('search');
            $products = Product::query()
                ->when($search, function ($query, $search){
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('stock', 'like', "%{$search}%")
                            ->orWhere('internal_price', 'like', "%{$search}%")
                            ->orWhere('profit_percentage', 'like', "%{$search}%")
                            ->orWhere('sale_price', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%");
                    });
                })
                ->with('orders')
                ->latest()
                ->paginate(8);
        return Inertia::render('Products/List', [
            'products' => $products,
            'filters' => [
                'search' => $search
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Products/New', [

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $Product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $Product)
    {
        return Inertia::render('Products/Edit', [

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $Product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $Product)
    {
        //
    }

    public function updateStock(Request $request, $id){
        try {
            $validate = $request->validate([
                'adjustment' => 'integer',
                'note' => 'string'
            ]);
            $product = Product::find($id);
            $addStock = $product->stock + $validate['adjustment'];
            $product->stock = $addStock;
            $product->note = $validate['note'];
            $product->save();

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el stock')->withInput();
        }
    }
}
