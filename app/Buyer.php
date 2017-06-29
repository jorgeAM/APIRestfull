<?php

namespace App;
use App\Transaction;

class Buyer extends User
{
	#relaciones 1-*
	public function transactions(){
		return $this->hasMany(Transaction::class);
  }
}
