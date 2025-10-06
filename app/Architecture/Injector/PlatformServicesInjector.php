<?php

namespace App\Architecture\Injector;

use App\Architecture\Services\Classes\User\UserMobileService;
use App\Architecture\Services\Classes\User\UserService;
use App\Architecture\Services\Classes\User\UserWebService;
use Illuminate\Support\ServiceProvider;

class PlatformServicesInjector extends ServiceProvider
{

    public function detectPlatform($webService, $mobileService)
    {
        if (request()->is('api/*/website/*'))
            return $webService;
        return $mobileService;
    }

    public function register(): void
    {
        $this->app->singleton(UserService::class, $this->detectPlatform(webService: UserWebService::class, mobileService: UserMobileService::class));
    }
}
