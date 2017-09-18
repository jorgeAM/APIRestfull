<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductController extends ApiController
{
    public function __construct(){
      $this->middleware('client')->only(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #buscamos todos los productos
        $products = Product::all();
        return $this->showAll($products);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        #retornamos el producto encontrado
        return $this->showOne($product);
    }

}
