<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate untuk fitur yang hanya bisa diakses oleh admin utama
        Gate::define('access-admin-utama', function (User $user) {
            return $user->role === 'admin_utama';
        });

        // Gate untuk fitur yang bisa diakses oleh semua jenis admin
        Gate::define('access-admin-features', function (User $user) {
            return in_array($user->role, ['admin_utama', 'admin_bidang']);
        });
    }
}