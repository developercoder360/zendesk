<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayContract;
use App\Services\MockPaymentGateway;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            PaymentGatewayContract::class,
            MockPaymentGateway::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $previousHandler = set_error_handler(function ($level, $message, $file = '', $line = 0) use (&$previousHandler) {
            if ($level === E_DEPRECATED && str_contains($message, 'Stancl\Tenancy\Database\Concerns\BelongsToTenant::$tenantIdColumn is deprecated')) {
                return true;
            }
            return $previousHandler ? $previousHandler($level, $message, $file, $line) : false;
        });

        $this->loadMigrationsFrom(database_path('migrations/tenant'));
        $this->configureDefaults();
        config(['livewire.inject_assets' => false]);
        
        Vite::useScriptTagAttributes(['data-navigate-track' => 'reload']);
        Vite::useStyleTagAttributes(['data-navigate-track' => 'reload']);
        Vite::usePreloadTagAttributes(['data-navigate-track' => 'reload']);

        Gate::before(function ($user, $ability) {
            // Check if the user has a global Super Admin role
            $isSuperAdmin = $user->roles->contains(function ($role) {
                return $role->name === 'Super Admin' && $role->tenant_id === null;
            });

            if ($isSuperAdmin) {
                return true;
            }

            // If operating within a tenant context, Owner bypasses permissions
            if (function_exists('tenant') && tenant() && $user->hasRole('Owner')) {
                return true;
            }
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
