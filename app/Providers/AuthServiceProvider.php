<?php

namespace App\Providers;

use App\Manager;
use App\Permission;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
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
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);
        foreach($this->getPermissions() as $permission){
            $gate->define($permission->name,function(Manager $manager) use($permission){
                return $manager->hasRole($permission->roles);
            });
        }
//        dd($gate);
    }


    protected function getPermissions(){
        return Permission::with('roles')->get();
    }
}
