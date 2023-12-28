<?php

namespace App\Providers;

use App\Events\OrderEvent;
use App\Listeners\MinusQtyProduct;
use App\Listeners\SendEmailToAdmin;
use App\Listeners\SendEmailToClient;
use App\Models\ProductCategory;
use App\Observers\ProductCategoryObserver;
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
        OrderEvent::class => [
            SendEmailToClient::class,
            SendEmailToAdmin::class,
            MinusQtyProduct::class
        ]
    ];

    protected $observers = [
        ProductCategory::class => [ProductCategoryObserver::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
