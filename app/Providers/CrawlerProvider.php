<?php

namespace App\Providers;

use App\Domain\Crawler\Contracts\UrlFinder;
use App\Domain\Crawler\Services\HttpUrlFinder;
use Illuminate\Support\ServiceProvider;

class CrawlerProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(UrlFinder::class, HttpUrlFinder::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

    }
}
