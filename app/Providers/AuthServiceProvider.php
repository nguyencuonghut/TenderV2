<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Supplier;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public static $permissions = [
        'create-role' => ['Admin'],
        'store-role' => ['Admin'],
        'edit-role' => ['Admin'],
        'update-role' => ['Admin'],
        'create-supplier' => ['Admin', 'Nhân viên Mua Hàng'],
        'store-supplier' => ['Admin', 'Nhân viên Mua Hàng'],
        'edit-supplier' => ['Admin', 'Nhân viên Mua Hàng'],
        'update-supplier' => ['Admin', 'Nhân viên Mua Hàng'],
        'destroy-supplier' => ['Admin'],
        'import-supplier' => ['Admin'],
        'create-admin' => ['Admin'],
        'store-admin' => ['Admin'],
        'edit-admin' => ['Admin'],
        'update-admin' => ['Admin'],
        'destroy-admin' => ['Admin'],
        'create-material' => ['Admin'],
        'store-material' => ['Admin'],
        'edit-material' => ['Admin'],
        'update-material' => ['Admin'],
        'destroy-material' => ['Admin'],
        'import-material' => ['Admin'],
        'create-user' => ['Admin', 'Nhân viên Mua Hàng'],
        'store-user' => ['Admin', 'Nhân viên Mua Hàng'],
        'edit-user' => ['Admin', 'Nhân viên Mua Hàng'],
        'update-user' => ['Admin', 'Nhân viên Mua Hàng'],
        'destroy-user' => ['Admin'],
        'import-user' => ['Admin'],
        'create-tender' => ['Admin', 'Nhân viên Mua Hàng'],
        'store-tender' => ['Admin', 'Nhân viên Mua Hàng'],
        'edit-tender' => ['Admin', 'Nhân viên Mua Hàng'],
        'update-tender' => ['Admin', 'Nhân viên Mua Hàng'],
        'destroy-tender' => ['Admin', 'Nhân viên Mua Hàng'],
        'cancel-tender' => ['Admin', 'Nhân viên Kiểm Soát'],
        'change-status' => ['Admin', 'Nhân viên Kiểm Soát'],
        'create-result' => ['Admin', 'Nhân viên Mua Hàng'],
        'store-result' => ['Admin', 'Nhân viên Mua Hàng'],
        'destroy-result' => ['Admin', 'Nhân viên Mua Hàng'],
        'audit-result' => ['Admin', 'Nhân viên Kiểm Soát'],
        'request-approve' => ['Admin', 'Nhân viên Kiểm Soát'],
        'create-propose' => ['Admin', 'Nhân viên Mua Hàng'],
        'destroy-propose' => ['Admin', 'Nhân viên Mua Hàng'],
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
