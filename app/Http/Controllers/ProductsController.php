<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $products = Product::query()
            ->when($search, function ($query, $search) {
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
        return Inertia::render('Products/New', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'name' => 'required|string',
                'internal_price' => 'required|integer',
                'profit_percentage' => 'required|integer',
                'stock' => 'required|integer'
            ]);

            $profit = ($validate['internal_price'] * $validate['profit_percentage']) / 100;
            $salePrice = $validate['internal_price'] + $profit;

            $product = Product::create([
                'name' => $validate['name'],
                'internal_price' => $validate['internal_price'],
                'profit_percentage' => $validate['profit_percentage'],
                'stock' => $validate['stock'],
                'sale_price' => $salePrice
            ]);

            return redirect()->route('product.index')->with('success', 'Producto creado correctamente');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el producto')->withInput();
        }
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
    public function edit($id)
    {
        $product = Product::where('id', $id)->first();
        if (!$product) {
            return redirect()->route('product.index')->with('error', 'Producto no encontrado');
        }
        return Inertia::render('Products/Edit', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $rules = [
                'id' => 'required|exists:products,id',
                'name' => 'required|string|max:200',
                'internal_price' => 'required|integer',
                'profit_percentage' => 'required|integer',
                'stock' => 'required|integer',
            ];
            $validate = $request->validate($rules);
            $product = Product::findOrFail($validate['id']);

            $product->name = $validate['name'];
            $product->internal_price = $validate['internal_price'];
            $product->profit_percentage = $validate['profit_percentage'];
            $product->stock = $validate['stock'];
            $product->sale_price = ceil((($validate['internal_price'] * $product->profit_percentage) / 100) + $validate['internal_price']);

            if ($product->stock > 0) {
                $product->status = Product::STATUS_ACTIVE;
            } else {
                $product->status = Product::STATUS_INACTIVE;
            }

            $product->save();

            $orders = $product->orders()->where('status', Order::STATUS_CREATED)->get();

            foreach ($orders as $order) {
                $oldPrice = $order->pivot->price;
                $newPrice = $product->sale_price;

                if ($oldPrice != $newPrice) {
                    $change = $newPrice - $oldPrice;

                    $order->internal_total -= $order->pivot->internal_subtotal;
                    $order->total -= $order->pivot->subtotal;

                    $order->save();

                    $order->pivot->update([
                        'price' => $newPrice,
                        'subtotal' => $order->pivot->quantity * $newPrice,
                        'internal_price' => $product->internal_price,
                        'internal_subtotal' => $order->pivot->quantity * $product->internal_price,
                        'change' => $change,
                    ]);

                    $order->internal_total += $order->pivot->quantity * $product->internal_price;
                    $order->total += $order->pivot->quantity * $newPrice;

                    $order->save();
                }
            }

            return redirect()->route('product.index')
                ->with('success', 'producto acutalizado.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el usuario')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $Product)
    {
        //
    }

    public function updateStock(Request $request, $id)
    {
        try {
            $validate = $request->validate([
                'adjustment' => 'integer',
                'note' => 'nullable|string'
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
