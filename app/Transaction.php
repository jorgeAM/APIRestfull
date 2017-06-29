<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	#atributos
	protected $fillable = [
		'quantity',
    'buyer_id',
    'product_id',
  ];    
}
