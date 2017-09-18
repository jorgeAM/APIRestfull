<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {   
        #creamos un array
        $transformedInput = [];
        #recorremos todo el request
        foreach ($request->request->all() as $input => $value) {
            #llenamos el array pero como llave usamos el metodo para conseguir a que valor transforma y le damos el mismo valor
            $transformedInput[$transformer::originalAttribute($input)] = $value;
        }

        #reemplazamos
        $request->replace($transformedInput);
        $response =  $next($request);
        if(isset($response->exception) && $response->exception instanceof ValidationException){
            $data = $response->getData();
            #creamos un array
            $transformedErrors = [];
            foreach ($data->error as $field => $error) {
                $transformedField = $transformer::transformedAttribute($field);
                $transformedErrors[$transformedField] = str_replace($field, $transformedField, $error);
            }
            $data->error = $transformedErrors;
            $response->setData($data);
        }
        return $response;
    }
}
