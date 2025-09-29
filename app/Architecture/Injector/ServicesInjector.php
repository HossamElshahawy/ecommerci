<?php

namespace App\Architecture\Injector;

use App\Architecture\Services\Classes\User\UserService;
use App\Architecture\Services\Interfaces\IUserService;
use Illuminate\Support\ServiceProvider;

class ServicesInjector extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(IUserService::class, UserService::class);
    }
}
