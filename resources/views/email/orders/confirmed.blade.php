<x-mail::message>
# ¡Gracias por tu compra, {{ $order->client->name }}!

Nos complace informarte que tu pedido se encuentra confirmado.

## Detalles del Pedido:

- **Número de pedido:** {{ $order->id }}
- **Fecha de pedido:** {{ $order->created_at->format('Y-m-d') }}
- **Total:** ${{ number_format($order->total, 0, ',', '.') }}

## Productos comprados:

<x-mail::table>
| Producto      | Precio Unitario | Cantidad | Subtotal |
| ------------- | :-------------: | -------: | --------: |
@foreach ($order->products as $product)
| {{ $product->name }} | ${{ number_format($product->pivot->price, 0, ',', '.') }} | {{ $product->pivot->quantity }} | ${{ number_format($product->pivot->subtotal, 0, ',', '.') }} |
@endforeach
</x-mail::table>

Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos.

{{-- <x-mail::button :url="'https://tusitio.com/pedidos/' . $order->id">
    Ver Pedido
</x-mail::button> --}}

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>