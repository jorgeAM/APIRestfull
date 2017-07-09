<?php

namespace App\Exceptions;

use Exception;
#llamamos al trait
use App\Traits\ApiResponser;
#Excepcion para autenticación
use Illuminate\Auth\AuthenticationException;
#Excepcion para modelo no encontrado
use Illuminate\Database\Eloquent\ModelNotFoundException;
#Excepcion para autorizacion
use Illuminate\Auth\Access\AuthorizationException;
#Excepcion para validación
use Illuminate\Validation\ValidationException;
#Excepcion cuando no encontramos URL
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
#Excepcion HTTP en general
use Symfony\Component\HttpKernel\Exception\HttpException;
#Elegimos mal el método
use Symfony \ Component \ HttpKernel \ Exception \ MethodNotAllowedHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        if($exception instanceof ModelNotFoundException){
            return $this->errorResponse('No se encontro dato, escribe bien Crrano!!', 404);
        }
        if($exception instanceof AuthenticationException){
            return $this->unauthenticated($request, $e);
        }
        if($exception instanceof AuthorizationException){
            return $this->errorResponse('No posees permisos para hacer eso Crrano!', 403);
        }
        if($exception instanceof NotFoundHttpException){
            return $this->errorResponse('No se encontro la URL, escribe bien Crrano!', 404);
        }
        if($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('Elige bien el método, Crrano!', 405);
        }
        if($exception instanceof HttpException){
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('No estas autenticado', 401);
    }

    #metodo para que devuelva un JSON -> use Illuminate\Foundation\Exceptions\Handler
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        return $this->errorResponse($errors, 422);
        #return response()->json($errors, 422);
    }
}
