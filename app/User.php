<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
#para poder usar el soft delete
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{   
  use Notifiable, SoftDeletes;

  const USUARIO_VERIFICADO = '1';
  const USUARIO_NO_VERIFICADO = '0';
  const USUARIO_ADMINISTRADOR = 'true';
  const USUARIO_NO_ADMINISTRADOR = 'false';

  protected $table = 'users';
  protected $dates = ['deleted_at'];

  /**
    * The attributes that are mass assignable.
    *
    * @var array
  */

  protected $fillable = [
    'name',
    'email',
    'password',
    'verified',
    'verification_token',
    'admin',
  ];

  /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
  */
  
  protected $hidden = [
    'password', 'remember_token', 'verification_token',
  ];

  public function esVerificado(){
    return $this->verified == User::USUARIO_VERIFICADO;
  }

  public function esAdministrador(){
    return $this->admin == User::USUARIO_ADMINISTRADOR;
  }

  public static function generarVerificationToken(){
    return str_random(40);
  }
}
