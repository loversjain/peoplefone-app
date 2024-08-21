<?php

namespace App\Providers;

use App\Repositories\Notification\Interfaces\NotificationRepositoryInterface;
use App\Repositories\Notification\NotificationRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\User\UserRepository;
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind the UserRepositoryInterface to UserRepository
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Bind the NotificationRepositoryInterface to NotificationRepository
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
