<?php

namespace App\Providers;

use App\Domain\Crawler\Contracts\UrlRepository;
use App\Domain\Crawler\Repositories\EloquentUrlRepository;
use App\Domain\Crawler\Repositories\RedisUrlRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Redis\Factory as RedisFactory;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(UrlRepository::class, function () {
            return new RedisUrlRepository(
                new EloquentUrlRepository(),
                app(RedisFactory::class)->connection('cache')->client(),
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
