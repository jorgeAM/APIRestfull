<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    #atributos
    protected $fillable = ['name', 'description'];
}
