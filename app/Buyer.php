<?php

namespace App;
use App\Transaction;
#scope de buyer
use App\Scopes\BuyerScope;

class Buyer extends User
{
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
