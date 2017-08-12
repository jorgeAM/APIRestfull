<?php

namespace App;

use App\Buyer;
use App\Product;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
  #para relacionarlo con la transformacion
  public $transformer = TransactionTransformer::class;

  use SoftDeletes;
  protected $dates = ['deleted_at'];
  
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
