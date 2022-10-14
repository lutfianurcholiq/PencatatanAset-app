<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;
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

        Gate::define('IsAdmin', function (User $user){
            return $user->role == 'admin';
        });

        Gate::define('IsManager', function (User $user){
            return $user->role == 'manager' || $user->role == 'admin';
        });

        Gate::define('IsStaff', function (User $user){
            return $user->role == 'staff' || $user->role == 'admin';
        });

        //
    }
}
