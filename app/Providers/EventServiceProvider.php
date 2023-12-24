<?php

namespace App\Providers;

use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\QrProfile;
use App\Models\Setting;
use App\Observers\Client\ClientClearCacheObserver;
use App\Observers\City\CityClearCacheObserver;
use App\Observers\Country\CountryClearCacheObserver;
use App\Observers\QrProfile\QrProfileClearCacheObserver;
use App\Observers\Setting\SettingClearCacheObserver;
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
        Country::observe(CountryClearCacheObserver::class);
        City::observe(CityClearCacheObserver::class);
        Setting::observe(SettingClearCacheObserver::class);
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
