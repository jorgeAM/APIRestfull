<?php

namespace App\Providers;

#carbon para manejar fechas
use Carbon\Carbon;
#llamamos a passport
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        #llamamos a las rutas de passport
        Passport::routes();
        #le damos un ciclo de vida al token
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        #le damos fecha para que se refresque el token
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}
