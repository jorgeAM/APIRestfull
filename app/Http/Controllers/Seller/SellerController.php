<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendedores = Seller::has('products')->get();
        return $this->showAll($vendedores);
        #return response()->json(['data' => $vendedores], 200);
    }

    /**
     * Display the specified resource.s
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
        #$vendedor = Seller::has('products')->findOrFail($id);
        return $this->showOne($seller);
        #return response()->json(['data' => $vendedor], 200);
    }

}
