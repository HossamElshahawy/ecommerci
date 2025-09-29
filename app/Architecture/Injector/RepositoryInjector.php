<?php

namespace App\Architecture\Injector;

use App\Architecture\Repositories\Classes\UserRepository;
use App\Architecture\Repositories\Interfaces\IUserRepository;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class RepositoryInjector extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(IUserRepository::class, function ($app) {
            return new UserRepository($app->make(User::class));
        });
    }
}
