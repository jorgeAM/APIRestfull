<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            #tranformación(el tipo de dato)
            'identificador' => (int)$buyer->id,
            'nombre' => (string)$buyer->name,
            'correo' => (string)$buyer->email,
            'esVerificado' => (int)$buyer->verified,
            'fechaCreación' => (string)$buyer->created_at,
            'fechaActualización' => (string)$buyer->updated_at,
            'fechaEliminación' => isset($buyer->deleted_at) ? (string)$buyer->deleted_at : null,
            #para hateOS
            'links' => [
              [
                'rel' => 'self',
                'href' => route('buyers.show', $buyer->id)
              ],
              [
                'rel' => 'buyer.categories',
                'href' => route('buyers.categories.index', $buyer->id)
              ],
              [
                'rel' => 'buyer.products',
                'href' => route('buyers.products.index', $buyer->id)
              ],
              [
                'rel' => 'buyer.sellers',
                'href' => route('buyers.sellers.index', $buyer->id)
              ],
              [
                'rel' => 'buyer.transactions',
                'href' => route('buyers.transactions.index', $buyer->id)
              ],
              [
                'rel' => 'user',
                'href' => route('users.show', $buyer->id)
              ]
            ]
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'nombre' => 'name',
            'correo' => 'email',
            'esVerificado' => 'verified',
            'fechaCreación' => 'created_at',
            'fechaActualización' => 'updated_at',
            'fechaEliminación' => 'deleted_at'
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
