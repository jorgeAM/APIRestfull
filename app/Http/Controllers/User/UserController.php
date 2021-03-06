<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Mail\UserCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Transformers\UserTransformer;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
{   
    #constructor para usar el middleware TransformInput
    public function __construct(){
      $this->middleware('auth:api')->except(['store', 'resend', 'verify']);
      $this->middleware('client')->only(['store', 'resend']);
      $this->middleware('transform.input:' . UserTransformer::class)->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        #llamamos a todos los users
        $usuarios = User::all();
        return $this->showAll($usuarios);
        #return response()->json(['data' => $usuarios], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        #validación
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);
        #valores sin necesidad de agregarlos
        $campos = $request->all();
        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken();
        $campos['admin'] = User::USUARIO_NO_ADMINISTRADOR;
        #creamos un nuevo usuario
        $usuario = User::create($campos);
        return $this->showOne($usuario, 201);
        #return response()->json(['data'=>$usuario], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        #llamamos al usuario
        #$usuario = User::findOrFail($id);
        return $this->showOne($user);
        #return response()->json(['data' => $usuario], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        #validación
        $this->validate($request, [
            'email' => 'email|unique:users,email,'.$usuario->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:'.User::USUARIO_ADMINISTRADOR.','.User::USUARIO_NO_ADMINISTRADOR
        ]);
        #actualizar lo que se tenga
        if($request->has('name')){
            $usuario->name = $request->name;
        }
        if($request->has('email') && $usuario->email != $request->email){
            $usuario->verified = User::USUARIO_NO_VERIFICADO;
            $usuario->verification_token = User::generarVerificationToken();
            $usuario->email = $request->email;
        }
        if($request->has('password')){
            $usuario->password = bcrypt($request->password);
        }
        if($request->has('admin')){
            if(!$usuario->esVerificado()){
                return $this->errorResponse('Solo usuarios verificados pueden hacer esto! Crrano', 409);
                #return response()->json(['error' => 'Solo usuarios verificados pueden hacer esto! Crrano', 'code' => 409], 409);
            }
            $usuario->admin = $request->admin;
        }
        if(!$usuario->isDirty()){
            return $this->errorResponse('Se debe actualizar al menos 1 valor! Crrano', 422);
            #return response()->json(['error' => 'Se debe actualizar al menos 1 valor! Crrano', 'code' => 422], 422);
        }
        #guardamos
        $usuario->save();
        return $this->showOne($usuario);
        #return response()->json(['data' => $usuario], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        #$usuario = User::findOrFail($id);
        $user->delete();
        return $this->showOne($user);
        #return response()->json(['data' => $usuario], 200);
    }

    #metodo para verificacion de correo
    public function verify($token){
      #buscamos usuario por token
      $user = User::where('verification_token', $token)->firstOrFail();
      #colocamos como verificado
      $user->verified = User::USUARIO_VERIFICADO;
      #anulamos verification_token
      $user->verification_token = null;
      #guardamos
      $user->save();
      return $this->showMessage('La cuenta fue verificada');
    }

    public function resend(User $user){
        if($user->esVerificado()){
            return $this->errorResponse('Usuario ya fue verificado', 409);
        }
        #intenta 5 veces despues de 100 ms
        retry(5, function() use ($user){
          Mail::to($user)->send(new UserCreated($user));
        }, 100);
        return $this->showMessage('Se reenvio el correo de verificación.');
    }
}
