<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        // Gateの設定
        Gate::define('admin', function($user){
            return $user->role === 1;
        });
        Gate::define('manager-higher', function($user){
            return $user->role > 0 && $user->role <= 5;
        });        
        Gate::define('user-higher', function($user){
            return $user->role > 0 && $user->role <= 9;
        });
    }
}
