<?php

namespace App;
use App\Product;

class Seller extends User
{
	#relacion 1-*
	public function products(){
   	return $this->hasMany(Product::class);
  }
}
