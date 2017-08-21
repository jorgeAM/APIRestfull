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
          'detalle' => (string)$product->description,
          'disponibles' => (int)$product->quantity,
          'estado' => (string)$product->status,
          #sera un URL
          'imagen' => url('img/{$product->image}'),
          'vendedor' => (int)$product->seller_id,
          'fechaCreación' => (string)$product->created_at,
          'fechaActualización' => (string)$product->updated_at,
          'fechaEliminación' => isset($product->deleted_at) ? (string)$product->deleted_at : null,
          #para hateOS
          'links' => [
              [
                'rel' => 'self',
                'href' => route('products.show', $product->id)
              ],
              [
                'rel' => 'product.buyers',
                'href' => route('products.buyers.index', $product->id)
              ],
              [
                'rel' => 'product.categories',
                'href' => route('products.categories.index', $product->id)
              ],
              [
                'rel' => 'product.transactions',
                'href' => route('products.transactions.index', $product->id)
              ],
              [
                'rel' => 'seller',
                'href' => route('sellers.show', $product->seller_id)
              ]
          ]
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'titulo' => 'name',
            'detalle' => 'description',
            'disponibles' => 'quantity',
            'estado' => 'status',
            'imagen' => 'image',
            'vendedor' => 'seller_id',
            'fechaCreación' => 'created_at',
            'fechaActualización' => 'updated_at',
            'fechaEliminación' => 'deleted_at'
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index){
        $attributes = [
            'id' => 'identificador',
            'name' => 'titulo',
            'description' => 'detalle',
            'quantity' => 'disponibles',
            'status' => 'estado',
            'image' => 'imagen',
            'seller_id' => 'vendedor',
            'created_at' => 'fechaCreación',
            'updated_at' => 'fechaActualización',
            'deleted_at' => 'fechaEliminación'
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
