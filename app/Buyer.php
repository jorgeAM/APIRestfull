<?php

namespace App;
use App\Transaction;
use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;

class Buyer extends User
{	
	#para relacionarlo con la transformacion
  	public $transformer = BuyerTransformer::class;

	#para usar el BuyerScope
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new BuyerScope);
    }

	#relaciones 1-*
	public function transactions(){
		return $this->hasMany(Transaction::class);
  }
}
