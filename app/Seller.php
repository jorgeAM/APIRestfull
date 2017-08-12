<?php

namespace App;
use App\Product;
use App\Scopes\SellerScope;
use App\Transformers\SellerTransformer;

class Seller extends User
{	
	#para relacionarlo con la transformacion
  	public $transformer = SellerTransformer::class;

	#para usar el BuyerScope
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new SellerScope);
    }

	#relacion 1-*
	public function products(){
   		return $this->hasMany(Product::class);
  	}
}
