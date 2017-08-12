<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            #tranformación(el tipo de dato)
            'identificador' => (int)$product->id,
            'titulo' => (string)$product->name,
            'detaller' => (string)$product->description,
            'disponibles' => (int)$product->quantity,
            'estado' => (string)$product->status,
            #sera un URL
            'imagen' => url('img/{$product->image}'),
            'vendedor' => (int)$product->seller_id,
            'fechaCreación' => (string)$product->created_at,
            'fechaActualización' => (string)$product->updated_at,
            'fechaEliminación' => isset($product->deleted_at) ? (string)$product->deleted_at : null
        ];
    }
}
