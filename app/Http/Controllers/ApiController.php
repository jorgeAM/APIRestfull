<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use ApiResponser;

    #constructor para usar el middleware TransformInput
    public function __construct(){
    }
}
