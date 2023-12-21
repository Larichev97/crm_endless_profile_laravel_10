<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\QrProfile;
use App\Observers\Client\ClientClearCacheObserver;
use App\Observers\QrProfile\QrProfileClearCacheObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // *** Очистка кэша при выполнении CRUD у моделей:
        Client::observe(ClientClearCacheObserver::class);
        QrProfile::observe(QrProfileClearCacheObserver::class);
        // ***
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
