<?php

//gère les directives blade @role
namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider
{

    public function register()
    {
        //
    }

    public function boot()
    {
        try {
            Permission::get()->map(function ($permission) {
                Gate::define($permission->nom, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            });
        } catch (\Exception $e) {
            report($e);
            return false;
        }

        //directives Blade custom @role @endrole
        Blade::directive('role', function ($role) {
             return "<?php if(auth()->check() && auth()->user()->hasRole({$role})) : ?>"; //return this if statement inside php tag
        });

        Blade::directive('endrole', function () {
             return "<?php endif; ?>"; //return this endif statement inside php tag
        });

    }
}
