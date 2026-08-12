<?php

namespace App\Providers;

use App\Models\Branch;
use App\Models\User;
use App\Models\WebsiteSetting;
use App\Services\Reminders\LogSmsGateway;
use App\Services\Reminders\LogWhatsAppGateway;
use App\Services\Reminders\SmsGatewayInterface;
use App\Services\Reminders\WhatsAppGatewayInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Swap these bindings to a real provider's implementation to go
        // live with SMS/WhatsApp — see config/reminders.php.
        $this->app->bind(SmsGatewayInterface::class, match (config('reminders.sms_driver')) {
            default => LogSmsGateway::class,
        });

        $this->app->bind(WhatsAppGatewayInterface::class, match (config('reminders.whatsapp_driver')) {
            default => LogWhatsAppGateway::class,
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Deferred until a view actually renders, so artisan commands
        // that boot the app before migrations run (e.g. `migrate`) are unaffected.
        View::composer('*', function ($view): void {
            $view->with('siteSettings', WebsiteSetting::allSettings());
        });

        View::composer(['components.footer', 'components.navbar'], function ($view): void {
            $view->with('navBranches', Branch::visible()->ordered()->get());
        });

        // Only a super admin manages other admin accounts.
        Gate::define('manage-users', fn (User $user) => $user->hasRole(User::ROLE_SUPER_ADMIN));

        // Super admin and clinic admin control clinic-wide configuration.
        Gate::define('manage-settings', fn (User $user) => $user->hasRole(
            User::ROLE_SUPER_ADMIN,
            User::ROLE_CLINIC_ADMIN,
        ));

        // Behind a load balancer / reverse proxy, requests can arrive as
        // plain HTTP even when the public-facing site is HTTPS-only —
        // force generated URLs (and the scheme Laravel sees) to https.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
