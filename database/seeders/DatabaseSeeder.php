<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Installment;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        echo "---CREANDO USUARIOS---\n";
        User::factory()->create([
            'name' => 'Sebastian Nope',
            'email' => 'sebas@email.com',
        ]);

        User::factory()->create([
            'name' => 'Fabian Combita',
            'email' => 'fabi@email.com',
        ]);

        echo "---CREANDO CLIENTES---\n";
        $clients = Client::factory()->count(30)->create();
        echo "---CREANDO PRODUCTOS---\n";
        $products = Product::factory()->state(['status' => Product::STATUS_ACTIVE])->count(300)->create();
        echo "---CREANDO ORDENES---\n";
        foreach ($clients as $client) {
            foreach ([Order::STATUS_COMPLETED, Order::STATUS_PENDING, Order::STATUS_CREATED, Order::STATUS_PARTIAL] as $status) {
                $order = Order::create([
                    'client_id' => $client->id,
                    'status' => $status
                ]);

                $selectedProducts = $products->random(rand(1, 10));
                $totalOrder = $internalTotalOrder = 0;

                foreach ($selectedProducts as $product) {
                    $quantity = rand(1, 5);
                    $price = $product->sale_price;
                    $subtotal = $price * $quantity;

                    $order->products()->attach($product->id, [
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal,
                        'internal_price' => $product->internal_price,
                        'internal_subtotal' => $product->internal_price * $quantity
                    ]);

                    $totalOrder += $subtotal;
                    $internalTotalOrder += $product->internal_price * $quantity;
                }

                $order->update(['total' => $totalOrder, 'internal_total' => $internalTotalOrder]);

                if ((rand(0, 100) < 50) && ($order->status == Order::STATUS_COMPLETED || $order->status == Order::STATUS_PARTIAL)) {
                    $installmentCount = rand(1, 5);
                    $installmentAmount = round($totalOrder / $installmentCount);
                    $paidInstallments = 0;

                    for ($i = 1; $i <= $installmentCount; $i++) {
                        $dueDate = now()->addDays($i * 15);
                        $amount = ($i === $installmentCount)
                            ? $totalOrder - ($installmentAmount * ($installmentCount - 1))
                            : $installmentAmount;

                        $paidAmount = 0;
                        $status = Installment::STATUS_PENDING;
                        $paidAt = null;

                        if ($order->status === Order::STATUS_COMPLETED) {
                            $paidAmount = $amount;
                            $status = Installment::STATUS_PAYED;
                            $paidAt = now()->subDays(rand(1, 30));
                        } elseif ($order->status === Order::STATUS_PARTIAL) {
                            if ($i <= rand(1, $installmentCount - 1)) {
                                $paidAmount = $amount;
                                $status = Installment::STATUS_PAYED;
                                $paidAt = now()->subDays(rand(1, 15));
                                $paidInstallments++;
                            }
                        }

                        Installment::create([
                            'order_id'     => $order->id,
                            'amount'       => $amount,
                            'due_date'     => $dueDate,
                            'paid_amount'  => $paidAmount,
                            'paid_at'      => $paidAt,
                            'status'       => $status,
                        ]);
                    }
                }
            }
        }

        echo "---CREANDO ROLES Y PERMISOS---\n";
        $this->call(RoleAndPermissionSeeder::class);

        echo "---CHAO CON ADIOS---\n";
    }
}
