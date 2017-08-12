<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

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
			return $this->successResponse(['data' => $collection, 'code' => 200]);
		}
		#conseguimos el transformador
		$transformer = $collection->first()->transformer;
		#ordenamos
		$collection = $this->sortData($collection, $transformer);
		#hacemos la paginación
		$collection = $this->paginate($collection);
		#transformamos
		$collection = $this->transformData($collection, $transformer);
		return $this->successResponse($collection, $code);
	}

	protected function showOne(Model $instance, $code=200){
		#sacamos el transformer
		$transformer = $instance->transformer;
		#transformamos
		$instance = $this->transformData($instance, $transformer);
		#retornamos
		return $this->successResponse($instance, $code);
	}

	protected function showMessage($message, $code=200){
		return $this->successResponse(['data' => $message], $code);
	}

	#metodo para ordenar
	protected function sortData(Collection $collection, $transformer){
		#
		if(request()->has('sort_by')){
			#el metodo es estatico, se llama ::
			$attribute = $transformer::originalAttribute(request()->sort_by);			
			#$collection = $collection->sortBy($attribute);
			$collection = $collection->sortBy->{$attribute};
		}
		return $collection;
	}

	#metodo que hará la paginación
	protected function paginate(Collection $collection){
		#reglas para cuando el usuario elija cuantos elementos por pagina
		$rules = [
			'per_page' => 'integer|min:2|max:50'
		];
		Validator::validate(request()->all(), $rules);
		#metodo propio de laravel
		$page = LengthAwarePaginator::resolveCurrentPage();
		#elemenos por pagina
		$perPage = 15;
		if(request()->has('per_page')){
			$perPage = (int)request()->per_page;
		}
		#resultado de cada pagina
		$results = $collection->slice(($page -1)*$perPage, $perPage)->values();

		$paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
			'path' => LengthAwarePaginator::resolveCurrentPath()
		]);
		$paginated->appends(request()->all());
		return $paginated;
	}

	#metodo para transformar
	protected function transformData($data, $transformer){
		#transformamos y pasamos a array
		$transformation = fractal($data, new $transformer)->toArray();
		#retornamos el array
		return $transformation;
	}
}