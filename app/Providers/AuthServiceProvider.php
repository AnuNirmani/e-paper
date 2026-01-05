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
        $this->registerPolicies();

        Gate::define('manage-users', fn (User $user): bool => $this->isAdminEmail($user));
        Gate::define('manage-customers', fn (User $user): bool => $this->isAdminEmail($user));
        Gate::define('manage-publications', fn (User $user): bool => $this->isAdminEmail($user));
        Gate::define('manage-copies', fn (User $user): bool => $this->isAdminEmail($user));
    }

    private function isAdminEmail(User $user): bool
    {
        $adminEmails = array_map('strtolower', config('permissions.admin_emails', []));

        return in_array(strtolower($user->email), $adminEmails, true);
    }
}
