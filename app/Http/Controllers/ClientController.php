<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $clients = Client::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Client/List', [
            'clients' => $clients,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Client/New', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'integer', 'max_digits:10'],
            'email' => ['required', 'email', 'unique:clients,email'],
            'address' => ['nullable', 'string', 'max:255']
        ]);

        Client::create([
            'name' => Str::title($request->name),
            'lastname' => Str::title($request->lastname),
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'address' => $request->address
        ]);

        return redirect()->route('client.index')->with('success', 'Cliente creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load(['orders' => function ($query) {
            $query->with(['products' => function ($query) {
                $query->select('products.id', 'products.name')
                    ->withPivot('quantity', 'price', 'internal_price');
            }]);
        }]);

        $completedOrders = $client->orders->filter(fn($order) => $order->status === Order::STATUS_COMPLETED);

        $topProducts = $completedOrders
            ->flatMap(fn($order) => $order->products->map(fn($product) => [
                'name' => $product->name,
                'quantity' => $product->pivot->quantity,
                'total_spent' => $product->pivot->quantity * $product->pivot->price,
                'total_internal' => $product->pivot->quantity * $product->pivot->internal_price,
                'profit' => ($product->pivot->price - $product->pivot->internal_price) * $product->pivot->quantity,
            ]))
            ->groupBy('name')
            ->map(fn($products, $name) => [
                'name' => $name,
                'total_quantity' => $products->sum('quantity'),
                'total_spent' => $products->sum('total_spent'),
                'total_internal' => $products->sum('total_internal'),
                'total_profit' => $products->sum('profit'),
            ])
            ->sortByDesc('total_quantity')
            ->take(5)
            ->values()
            ->all();

        $completedOrders = $client->orders->filter(fn($order) => $order->status === Order::STATUS_COMPLETED);

        $totalProfit = $completedOrders->sum(fn($order) => $order->products->sum(
            fn($product) => ($product->pivot->price - $product->pivot->internal_price) * $product->pivot->quantity
        ));

        $totalProductsSold = $completedOrders->sum(fn($order) => $order->products->sum(
            fn($product) => $product->pivot->quantity
        ));

        $averageProfit = round($totalProductsSold > 0 ? $totalProfit / $totalProductsSold : 0);

        return Inertia::render('Client/Detail', [
            'client' => $client,
            'topProducts' => $topProducts,
            'totalProfit' => $totalProfit,
            'averageProfit' => $averageProfit
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return Inertia::render('Client/Edit', [
            'client' => $client
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'integer', 'max_digits:10'],
            'email' => ['required', 'email', Rule::unique('clients', 'email')->ignore($request->id)],
            'address' => ['nullable', 'string', 'max:255']
        ]);

        $client = Client::find($request->id);

        $client->fill([
            'name' => Str::title($request->name),
            'lastname' => Str::title($request->lastname),
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'address' => $request->address
        ]);

        if (!$client->isDirty()) {
            return redirect()->back()->withErrors(['error' => 'Cliente no modificado.']);
        }

        $client->save();

        return redirect()->route('client.index')->with('success', 'Cliente actualizado correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Client::destroy($id);
        return redirect()->route('client.index')->with('success', 'Cliente eliminado correctamente.');
    }

    public function newOrder(Client $client)
    {
        $products = Product::where('status', Product::STATUS_ACTIVE)->get();

        return Inertia::render('Client/NewOrder', [
            'client' => $client,
            'products' => $products,
        ]);
    }

    public function attachOrder(Request $request, Client $client)
    {
        $request->validate([
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
            'products.*.sale_price' => ['required', 'integer', 'min:0'],
            'products.*.internal_price' => ['required', 'integer', 'min:0'],
        ]);

        $total = 0;
        $internalTotal = 0;

        foreach ($request->products as $product) {
            $subtotal = $product['sale_price'] * $product['quantity'];
            $internalSubtotal = $product['internal_price'] * $product['quantity'];

            $total += $subtotal;
            $internalTotal += $internalSubtotal;
        }

        $order = Order::create([
            'client_id' => $client->id,
            'status' => Order::STATUS_CREATED,
            'total' => $total,
            'internal_total' => $internalTotal,
        ]);

        $orderProducts = [];
        foreach ($request->products as $product) {
            $orderProducts[$product['id']] = [
                'quantity' => $product['quantity'],
                'price' => $product['sale_price'],
                'subtotal' => $product['sale_price'] * $product['quantity'],
                'internal_price' => $product['internal_price'],
                'internal_subtotal' => $product['internal_price'] * $product['quantity'],
            ];

            $modelProduct = Product::find($product['id']);
            $modelProduct->stock -= $product['quantity'];

            if ($modelProduct->stock < 1) {
                $modelProduct->status = Product::STATUS_INACTIVE;
            }

            $modelProduct->save();
        }

        $order->products()->attach($orderProducts);

        return redirect()->route('order.edit', $order->id)->with('success', 'Pedido creado exitosamente.');
    }
}
