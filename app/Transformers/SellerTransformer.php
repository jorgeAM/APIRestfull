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
            'fechaEliminación' => isset($seller->deleted_at) ? (string)$seller->deleted_at : null
        ];
    }
}
