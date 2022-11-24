<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
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

        //autorisation sur l'édition des utilisateurs
        Gate::define('edit-users', function($user)
        {
            return $user->isAdmin();
        });

        
        //autorisation sur la suppression des utilisateurs
        Gate::define('delete-users', function($user)
        {
            return $user->isAdmin();
        });


         //autorisation sur la gestion  des utilisateurs


         Gate::define('manage-users', function($user)
         {
             return $user->hasAnyRole(['SuperAdmin','admin']);
         });
    }
}
