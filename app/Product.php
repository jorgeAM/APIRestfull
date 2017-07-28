<?php

namespace App;

use App\Seller;
use App\Category;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
#para poder usar el soft delete
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  
	const PRODUCTO_DISPONIBLE = 'disponible';
	const PRODUCTO_NO_DISPONIBLE = 'no disponible';
  
  #atributos
  protected $fillable = [
   	'name',
   	'description',
   	'quantity',
   	'status',
   	'image',
   	'seller_id'
  ];

  #ocultar pivote de JSON
  protected $hidden = ['pivot'];

  public function estaDisponible(){
  	return $this->status == Product::PRODUCTO_DISPONIBLE;
  }

  #relacione *-1
  public function seller(){
    return $this->belongsTo(Seller::class);
  }

  #relacion 1-*
  public function transactions(){
    return $this->hasMany(Transaction::class);
  }
  
  #relacion *-*
  public function categories(){
    return $this->belongsToMany(Category::class);
  }
}
