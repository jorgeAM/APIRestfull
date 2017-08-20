<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            #tranformación(el tipo de dato)
            'identificador' => (int)$transaction->id,
            'cantidad' => (int)$transaction->quantity,
            'comprador' => (int)$transaction->buyer_id,
            'producto' => (int)$transaction->product_id,
            'fechaCreación' => (string)$transaction->created_at,
            'fechaActualización' => (string)$transaction->updated_at,
            'fechaEliminación' => isset($transaction->deleted_at) ? (string)$transaction->deleted_at : null,
            #para hateOS
            'links' => [
              [
                'rel' => 'self',
                'href' => route('transactions.show', $transaction->id)
              ],
              [
                'rel' => 'transaction.categories',
                'href' => route('transactions.categories.index', $transaction->id)
              ],
              [
                'rel' => 'transaction.seller',
                'href' => route('transactions.sellers.index', $transaction->id)
              ],
              [
                'rel' => 'buyer',
                'href' => route('buyers.show', $transaction->buyer_id)
              ],
              [
                'rel' => 'product',
                'href' => route('products.show', $transaction->product_id)
              ]
            ]
        ];
    }

    public static function ss(){
        $attributes = [
            'identificador' => 'id',
            'cantidad' => 'quantity',
            'comprador' => 'buyer_id',
            'producto' => 'product_id',
            'fechaCreación' => 'created_at',
            'fechaActualización' => 'updated_at',
            'fechaEliminación' => 'deleted_at'
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
