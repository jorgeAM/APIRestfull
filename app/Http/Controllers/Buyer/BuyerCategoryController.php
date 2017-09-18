<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{
    public function __construct(){
        parent::__construct()
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        #varias listas de categories
        $categories = $buyer->transactions()->with('product.categories')->get()->pluck('product.categories')
        #para que se junten todo en 1
        ->collapse()
        ->unique('id')
        ->values();
        return $this->showAll($categories);
    }

}
