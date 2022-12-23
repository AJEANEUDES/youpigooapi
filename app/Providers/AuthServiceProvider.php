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
        Gate::define('edit-users', function ($user) {
            return $user->isAdmin();
        });

        //autorisation sur l'édition des hotels
        Gate::define('edit-hotels', function ($user) {
            return $user->isHotel();
        });


        //autorisation sur l'édition des compagnies
        Gate::define('edit-compagnies', function ($user) {
            return $user->isCompagnie();
        });


        //autorisation sur l'édition des clients
        Gate::define('edit-clients', function ($user) {
            return $user->isClient();
        });






        //autorisation sur la suppression des utilisateurs
        Gate::define('delete-users', function ($user) {
            return $user->isAdmin();
        });


        //autorisation sur la suppression des clients
        Gate::define('delete-clients', function ($user) {
            return $user->isClient();
        });


        //autorisation sur la suppression des compagnies
        Gate::define('delete-compagnies', function ($user) {
            return $user->isCompagnie();
        });



        //autorisation sur la suppression des hotels
        Gate::define('delete-hotels', function ($user) {
            return $user->isHotel();
        });




        //autorisation sur la gestion  des utilisateurs


        Gate::define('manage-users', function ($user) {
            return $user->hasAnyRole(['SuperAdmin', 'Admin',]);
        });


        //autorisation sur la gestion  des hotels


        // Gate::define('manage-hotels', function ($user) {
        //     return $user->hasAnyRole(['SuperAdmin','Hotel']);
        // });


        
        // //autorisation sur la gestion  des compagnies


        // Gate::define('manage-compagnies', function ($user) {
        //     return $user->hasAnyRole(['Compagnie']);
        // });


        //   //autorisation sur la gestion  des clients


        //   Gate::define('manage-clients', function ($user) {
        //     return $user->hasAnyRole(['Client']);
        // });



    }
}
