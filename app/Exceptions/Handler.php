<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony \ Component \ HttpKernel \ Exception \ MethodNotAllowedHttpException;

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
            return $this->unauthenticated($request, $exception);
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
        if($exception instanceof QueryException){
            $codigo = $exception->errorInfo[1];
            if($codigo == 1451){
                return $this->errorResponse('No puedes borrar el registro por que esta relacionado con otro, crrano!', 409);
            }
        }
        if($exception instanceof TokenMismatchException){
            return redirect()->back()->withInput($request->input());
        }
        if(config('app.debug')){
            return parent::render($request, $exception);
        }
        return $this->errorResponse('agg tmr cerrano, algo paso.', 500);
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
        if($this->isFrontend($request)){
            return redirect()->guest('login');
        }
        return $this->errorResponse('No estas autenticado', 401);
    }

    #metodo para que devuelva un JSON -> use Illuminate\Foundation\Exceptions\Handler
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        if($this->isFrontend($request)){
            return $request->ajax() ? response()->json($errors, 422) : redirect()->back()->withInput($request->input())->withErrors($errors);
        }
        return $this->errorResponse($errors, 422);
    }

    private function isFrontend($request){
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
