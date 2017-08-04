<?php

namespace App\Http\Controllers\Seller;

use App\User;
use App\Seller;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;
        return $this->showAll($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        #validación
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image'
        ]);
        #conseguimos todos los datos
        $data = $request->all();
        $data['status'] = Product::PRODUCTO_DISPONIBLE;
        #guardamos imagen
        $data['image'] = $request->image->store('');
        $data['seller_id'] = $seller->id;
        #creamos producto
        $product = Product::create($data);
        return $this->showOne($product, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        #validación        
        $this->validate($request, [
            'quantity' => 'integer|min:1',
            'status' => 'in:'.Product::PRODUCTO_DISPONIBLE.','.Product::PRODUCTO_NO_DISPONIBLE,
            'image' => 'image'
        ]);
        if($seller->id != $product->seller_id){
            return $this->errorResponse('El vendedor especificado no tiene este producto, Crrano!', 422);
        }
        $product->fill($request->intersect([
          'name',
          'description',
          'quantity'
        ]));

        if($request->has('status')){
          $product->status = $request->status;
          if($product->estaDisponible() && $product->categories()->count() == 0){
            return $this->errorResponse('El producto al menos debe tener un categoria, agg Crrano!'. 409);
          }
        }
        #si tiene un archivo la peticion
        if($request->hasFile('image')){
            #borramos la imagen que tenia el producto
            Storage::delete($product->image);
            #agregamos la nueva imagen
            $product->image = $request->image->store('');
        }

        if($product->isClean()){
          return $this->errorResponse('debes cambiar algo, Crrano', 422);
        }

        $product->save();
        return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        if($seller->id != $product->seller_id){
          return $this->errorResponse('El vendedor especificado no tiene este producto, Crrano!', 422);
        }
        #nos permite interactiar con el sistema de archivos
        Storage::delete($product->image);
        $product->delete();
        return $this->showOne($product);
    }
}
