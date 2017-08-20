<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            #tranformación(el tipo de dato)
            'identificador' => (int)$seller->id,
            'nombre' => (string)$seller->name,
            'correo' => (string)$seller->email,
            'esVerificado' => (int)$seller->verified,
            'fechaCreación' => (string)$seller->created_at,
            'fechaActualización' => (string)$seller->updated_at,
            'fechaEliminación' => isset($seller->deleted_at) ? (string)$seller->deleted_at : null,
            #para hateOS
            'links' => [
              [
                'rel' => 'self',
                'href' => route('sellers.show', $seller->id)
              ],
              [
                'rel' => 'seller.buyers',
                'href' => route('sellers.buyers.index', $seller->id)
              ],
              [
                'rel' => 'seller.categories',
                'href' => route('sellers.categories.index', $seller->id)
              ],
              [
                'rel' => 'seller.products',
                'href' => route('sellers.products.index', $seller->id)
              ],
              [
                'rel' => 'seller.transactions',
                'href' => route('sellers.transactions.index', $seller->id)
              ],
              [
                'rel' => 'user',
                'href' => route('users.show', $seller->id)
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
