<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmed;
use App\Models\Installment;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $orders = Order::query()
            ->with('products', 'client')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $words = explode(' ', $search);

                    foreach ($words as $word) {
                        $query->where(function ($subQuery) use ($word) {
                            $subQuery->where('id', 'like', "%{$word}%")
                                ->orWhereHas('client', function ($clientQuery) use ($word) {
                                    $clientQuery->where('name', 'like', "%{$word}%")
                                        ->orWhere('lastname', 'like', "%{$word}%");
                                });
                        });
                    }
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();


        return Inertia::render('Orders/List', [
            'orders' => $orders,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function edit(Order $order)
    {
        $order->load('products', 'client', 'installments');
        $products = Product::where('status', Product::STATUS_ACTIVE)->get();
        //dd($order->products);
        return Inertia::render('Orders/Edit', [
            'order' => $order,
            'client' => $order->client,
            'products' => $products,
            'has_installments' => $order->installments()->exists()
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'exists:products,id'],
            'products.*.pivot.quantity' => ['required', 'integer', 'min:1'],
            'products.*.sale_price' => ['required', 'integer', 'min:0'],
            'products.*.internal_price' => ['required', 'integer', 'min:0'],
            'client' => ['required', 'integer', 'exists:clients,id'],
            'order' => ['required', 'integer', 'exists:orders,id']
        ]);

        $order = Order::find($request->order);

        $orderProducts = $order->products()->withPivot('quantity')->get();

        $currentProductIds = $orderProducts->pluck('id')->toArray();
        $requestProductIds = collect($request->products)->pluck('id')->toArray();

        $productsToDelete = array_diff($currentProductIds, $requestProductIds);

        if (!empty($productsToDelete)) {
            foreach ($productsToDelete as $productId) {
                $this->updateStockOnDelete($productId, $order);
            }

            $order->products()->detach($productsToDelete);
        }

        $total = 0;
        $internalTotal = 0;

        foreach ($request->products as $productData) {
            $productId = $productData['id'];
            $quantity = $productData['pivot']['quantity'];
            $price = $productData['sale_price'];
            $internal_price = $productData['internal_price'];

            $existingProduct = $orderProducts->firstWhere('id', $productId);
            if ($existingProduct) {
                $this->updateStockOnUpdate($productId, $existingProduct->pivot->quantity, $quantity);
                $order->products()->updateExistingPivot($productId, [
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $price * $quantity,
                    'internal_price' => $internal_price,
                    'internal_subtotal' => $quantity * $internal_price
                ]);
            } else {
                $order->products()->attach($productId, [
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $price * $quantity,
                    'internal_price' => $internal_price,
                    'internal_subtotal' => $quantity * $internal_price
                ]);
                $this->updateStockOnAttach($productId, $quantity);
            }

            $total += $price * $quantity;
            $internalTotal += $internal_price * $quantity;
        }

        $order->update([
            'internal_total' => $internalTotal,
            'total' => $total
        ]);

        return redirect()->route('order.edit', $order->id)->with('success', 'Pedido actualizado exitosamente.');
    }

    private function updateStockOnDelete($productId, $order)
    {
        $product = Product::find($productId);
        if ($product) {
            $quantityToRemove = $order->products()->where('product_id', $productId)->first()->pivot->quantity;
            $product->stock += $quantityToRemove;

            $this->updateProductStatus($product);
        }
    }

    private function updateStockOnUpdate($productId, $currentQuantity, $newQuantity)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->stock += $currentQuantity;
            $product->stock -= $newQuantity;

            $this->updateProductStatus($product);
        }
    }

    private function updateStockOnAttach($productId, $quantity)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->stock -= $quantity;

            $this->updateProductStatus($product);
        }
    }

    private function updateProductStatus($product)
    {
        if ($product->stock < 1) {
            $product->status = Product::STATUS_INACTIVE;
        } else {
            $product->status = Product::STATUS_ACTIVE;
        }

        $product->save();
    }

    public function changeStatus(Request $request)
    {
        $order = Order::find($request->order);

        $order->status = $request->status;
        $order->save();

        /* if ($request->status == Order::STATUS_PENDING) {
            Mail::to($order->client->email)->send(new OrderConfirmed($order));
        } */

        return redirect()->route('order.edit', $order->id)->with('success', 'Pedido actualizado exitosamente.');
    }

    public function destroy(Order $order)
    {
        foreach ($order->products as $product) {
            $product->stock += $product->pivot->quantity;
            $this->updateProductStatus($product);
        }
        $order->products()->detach();
        $order->delete();

        return redirect()->route('order.index')->with('success', 'Orden eliminada correctamente.');
    }

    public function storeInstallments(Request $request)
    {
        $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'installments.*.amount' => ['required', 'integer', 'min:1'],
            'installments.*.due_date' => ['required', 'date', 'date_format:Y-m-d']
        ]);

        $order = Order::find($request->order_id);

        $validate = 0;
        foreach ($request->installments as $keyCuota => $cuota) {
            $validate += $cuota['amount'];
        }

        if ($validate !== $order->total) {
            return redirect()->route('order.edit', $order->id)->with('error', 'El valor de las cuotas no concuerdan con el total de la orden');
        }

        foreach ($request->installments as $keyCuota => $cuota) {
            Installment::create([
                'order_id' => $order->id,
                'amount' => $cuota['amount'],
                'due_date' => $cuota['due_date'],
                'status' => Installment::STATUS_PENDING
            ]);
        }

        return redirect()->route('order.edit', $order->id)->with('success', 'Cuotas creadas exitosamente.');
    }

    public function updateInstallments(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'integer', 'min:1'],
            'due_date' => ['required', 'date', 'date_format:Y-m-d H:i:s'],
            'status' => ['required', 'in:' . implode(',', [Installment::STATUS_PAYED, Installment::STATUS_PENDING])],
        ]);

        $installment = Installment::find($request->id);

        $order = Order::find($installment->order_id);

        $installment->update([
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        return redirect()->route('order.edit', $order->id)->with('success', 'Cuotas actualizadas exitosamente.');
    }
}
