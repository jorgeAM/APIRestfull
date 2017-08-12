<?php

namespace App\Transformers;

#por ser transformación del modelo User
use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            #tranformación(el tipo de dato)
            'identificador' => (int)$user->id,
            'nombre' => (string)$user->name,
            'correo' => (string)$user->email,
            'esVerificado' => (int)$user->verified,
            #quiere que retorne
            'esAdminitrador' => ($user->admin == 'true'),
            'fechaCreación' => (string)$user->created_at,
            'fechaActualización' => (string)$user->updated_at,
            'fechaEliminación' => isset($user->deleted_at) ? (string)$user->deleted_at : null
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'nombre' => 'name',
            'correo' => 'email',
            'esVerificado' => 'verified',
            'esAdminitrador' => 'admin',
            'fechaCreación' => 'created_at',
            'fechaActualización' => 'updated_at',
            'fechaEliminación' => 'deleted_at'
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

}
