<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  #atributos
  protected $fillable = ['name', 'description'];

  #relacion *-*
	public function products(){
  	return $this->belongsToMany(Product::class);
  }
}
