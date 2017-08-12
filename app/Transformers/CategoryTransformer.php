<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            #tranformación(el tipo de dato)
            'identificador' => (int)$category->id,
            'titulo' => (string)$category->name,
            'detalles' => (string)$category->description,
            'fechaCreación' => (string)$category->created_at,
            'fechaActualización' => (string)$category->updated_at,
            'fechaEliminación' => isset($category->deleted_at) ? (string)$category->deleted_at : null
        ];
    }
}
