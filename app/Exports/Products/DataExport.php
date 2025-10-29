<?php

namespace App\Exports\Products;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataExport implements FromCollection, WithHeadings
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'stock' => $p->stock,
                'internal_price' => $p->internal_price,
                'sale_price' => $p->sale_price,
                'status' => $p->status,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID','Nombre','Stock','Precio interno','Precio venta','Estado'];
    }
}