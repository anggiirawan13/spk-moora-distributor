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

        Gate::define('admin', function ($user) {
            return $user->is_admin == 1;
        });

        Gate::define('import-excel', function ($user) {
            return $user->is_admin == 1 || $user->role === 'staf';
        });

        Gate::define('approve-import-admin', function ($user) {
            return $user->is_admin == 1;
        });

        Gate::define('approve-import-director', function ($user) {
            return $user->role === 'direktur_utama';
        });

        Gate::define('view-import-approval', function ($user) {
            return $user->is_admin == 1 || $user->role === 'direktur_utama';
        });
    }
}
