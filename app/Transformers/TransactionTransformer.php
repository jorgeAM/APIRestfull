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
            'fechaEliminación' => isset($transaction->deleted_at) ? (string)$transaction->deleted_at : null
        ];
    }
}
