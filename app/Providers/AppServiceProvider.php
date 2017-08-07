<?php

namespace App\Providers;
use App\User;
use App\Product;
use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        #para el error de utf8mb4
        Schema::defaultStringLength(191);
        #cuando un usuario es creado
        User::created(function($user){
            #enviamos correo
            Mail::to($user)->send(new UserCreated($user));
        });
        #cuando un usuario es actualizado
        User::updated(function($user){
            #asegurarnos que el email haya sido modificado
            if($user->isDirty('email')){
              #enviamos correo
              Mail::to($user)->send(new UserMailChanged($user));
            }
        });

        #cuando ya no hay stock que cambie a NO DISPONIBLE automaticamente
        Product::updated(function($product){
            if($product->quantity == 0 && $product->estaDisponible()){
                $product->status = Product::PRODUCTO_NO_DISPONIBLE;
                $product->save();
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
