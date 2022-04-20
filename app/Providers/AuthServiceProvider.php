<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Supplier;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public static $permissions = [
        'create-supplier' => ['Admin', 'Nhân viên Mua Hàng'],
        'store-supplier' => ['Admin', 'Nhân viên Mua Hàng'],
        'edit-supplier' => ['Admin', 'Nhân viên Mua Hàng'],
        'update-supplier' => ['Admin', 'Nhân viên Mua Hàng'],
        'destroy-supplier' => ['Admin'],
        'create-admin' => ['Admin'],
        'store-admin' => ['Admin'],
        'edit-admin' => ['Admin'],
        'update-admin' => ['Admin'],
        'destroy-admin' => ['Admin'],
    ];

    /**
     * The policy mappings for the application.
     *
     * @var array
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

        Gate::before(
            function (Admin $admin, $ability) {
                if ($admin->role->name === 'Admin') {
                    return true;
                }
            }
        );

        foreach (self::$permissions as $action => $roles) {
            Gate::define(
                $action,
                function (Admin $admin) use ($roles) {
                    if (in_array($admin->role->name, $roles)) {
                        return true;
                    }
                }
            );
        }
    }
}
