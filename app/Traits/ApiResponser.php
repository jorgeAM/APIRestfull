<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser{
	private function successResponse($data, $code){
		return response()->json($data, $code);
	}

	protected function errorResponse($message, $code){
		return response()->json(['error' => $message, 'code' => $code], $code);	
	}

	protected function showAll(Collection $collection, $code=200){
		if($collection->isEmpty()){
			#no transformamos nada, porque no hay nada
			return $this->successResponse($collection);
		}
		#conseguimos el transformador
		$transformer = $collection->first()->transformer;
		#transformamos
		$collection = $this->transformData($collection, $transformer);
		return $this->successResponse(['data' => $collection], $code);
	}

	protected function showOne(Model $instance, $code=200){
		return $this->successResponse(['data' => $instance], $code);
	}

	protected function showMessage($message, $code=200){
		return $this->successResponse(['data' => $message], $code);
	}

	protected function transformData($data, $transformer){
		#transformamos y pasamos a array
		$transformation = fractal($data, new $transformer)->toArray();
		#retornamos el array
		return $transformation;
	}
}