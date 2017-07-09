<?php

namespace App;
use App\Product;
#scope de seller
use App\Scopes\SellerScope;

class Seller extends User
{
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
