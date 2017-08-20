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
          'fechaEliminación' => isset($category->deleted_at) ? (string)$category->deleted_at : null,
          #para hateOS
          'links' => [
              [
                'rel' => 'self',
                'href' => route('categories.show', $category->id)
              ],
              [
                'rel' => 'category.buyers',
                'href' => route('categories.buyers.index', $category->id)
              ],
              [
                'rel' => 'category.products',
                'href' => route('categories.products.index', $category->id)
              ],
              [
                'rel' => 'category.sellers',
                'href' => route('categories.sellers.index', $category->id)
              ],
              [
                'rel' => 'category.transactions',
                'href' => route('categories.transactions.index', $category->id)
              ],
          ]
        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'titulo' => 'name',
            'detalles' => 'description',
            'fechaCreación' => 'created_at',
            'fechaActualización' => 'updated_at',
            'fechaEliminación' => 'deleted_at'
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
