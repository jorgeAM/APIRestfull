<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transformers\CategoryTransformer;

class CategoryController extends ApiController
{
    #constructor para usar el middleware TransformInput
    public function __construct(){
      $this->middleware('client')->only(['index', 'show']);
      $this->middleware('auth:api')->except(['index', 'show']);
      $this->middleware('transform.input:' . CategoryTransformer::class)->only(['store', 'update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #llamamos a todos las categorias
        $categories = Category::all();
        return $this->showAll($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        #validación
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required'
        ]);
        #creamos un nuevo usuario
        $category = Category::create($request->all());
        return $this->showOne($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->showOne($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        #intersect = solo los datos del array
        $category->fill($request->intersect([
            'name',
            'description'
        ]));
        #si no se agrego ningun dato nuevo
        if($category->isClean()){
            return $this->errorResponse('Debes actualizar por lo menos 1 valor, Crrano!',422);
        }
        #guardamos los cambios
        $category->save();
        #mostramos los cambios
        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        #borramos registro
        $category->delete();
        return $this->showOne($category);
    }
}
