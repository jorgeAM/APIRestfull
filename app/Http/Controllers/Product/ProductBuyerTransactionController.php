<?php

namespace App\Http\Controllers\Product;

use App\User;
use App\Product;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use App\Transformers\TransactionTransformer;

class ProductBuyerTransactionController extends ApiController
{
    #constructor para usar el middleware TransformInput
    public function __construct(){
      parent::__construct();
      $this->middleware('transform.input:' . TransactionTransformer::class)->only(['store']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        #validación
        $this->validate($request, [
            'quantity' => 'required|integer|min:1'
        ]);

        #ver que el comprador y el vendedor no sean la misma persona
        if($buyer->id == $product->seller_id){
            return $this->errorResponse('Crrano no puedes comprar tu propio producto.', 409);
        }
        #comprador debe estar verificado
        if(!$buyer->esVerificado()){
            return $this->errorResponse('Crrano debes verificar la cuenta, si quieres comprar.', 409);
        }
        #usuario debe estar verificado
        if(!$product->seller->esVerificado()){
            return $this->errorResponse('El Crrano que te vende debes verificar la cuenta.', 409);
        }
        #producto disponible
        if(!$product->estaDisponible()){
            return $this->errorResponse('Producto no disponible', 409);
        }
        #si se quiere comprar un cantidad mayor a la que hay en stock
        if($product->quantity < $request->quantity){
            return $this->errorResponse('Crrano, no hay mucho stock!!', 409);
        }
        #en caso de que se quiera hacer varias transacciones con el mismo producto a la vez, si falla en algo
        #NO PASA NADA y todo sigue igual
        return DB::transaction(function() use ($request, $product, $buyer){
            #reducimos de 1 la cantidad
            $product->quantity -= $request->quantity;
            #guardamos
            $product->save();
            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id
            ]);
            #retornamos lo que agregamos
            return $this->showOne($transaction, 201);
        });
    }
}
