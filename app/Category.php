<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];

  #para relacionarlo con la transformacion
  public $transformer = CategoryTransformer::class;
	
  #atributos
  protected $fillable = ['name', 'description'];

  #ocultar el pivot de JSON
  protected $hidden = ['pivot'];

  #relacion *-*
	public function products(){
  	return $this->belongsToMany(Product::class);
  }
}
