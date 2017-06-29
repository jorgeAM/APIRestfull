<?php

namespace App;

use App\Buyer;
use App\Product;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
	#atributos
	protected $fillable = [
		'quantity',
    'buyer_id',
    'product_id',
  ];

  #relacion *-1
  public function buyer(){
  	return $this->belongsTo(Buyer::class);
  }

  #relacion *-1
  public function product(){
  	return $this->belongsTo(Product::class);
  }
}
