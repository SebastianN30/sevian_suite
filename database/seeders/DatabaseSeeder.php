<?php

namespace Database\Seeders;

use App\Models\Client;
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
            foreach ([Order::STATUS_COMPLETED, Order::STATUS_CANCELLED, Order::STATUS_PENDING] as $status) {
                $order = Order::create([
                    'client_id' => $client->id,
                    'status' => $status
                ]);

                $selectedProducts = $products->random(rand(1, 10));
                $totalOrder = 0;

                foreach ($selectedProducts as $product) {
                    $quantity = rand(1, 5);
                    $price = $product->sale_price;
                    $subtotal = $price * $quantity;

                    $order->products()->attach($product->id, [
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal
                    ]);

                    $totalOrder += $subtotal;
                }

                $order->update(['total' => $totalOrder]);
            }
        }

        echo "---CHAO CON ADIOS---\n";
    }
}
