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
            'fechaEliminación' => isset($buyer->deleted_at) ? (string)$buyer->deleted_at : null
        ];
    }
}
