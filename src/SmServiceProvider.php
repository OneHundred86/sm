<?php

namespace Oh86\Sm;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;

class SmServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath($raw = __DIR__.'/../config/sm.php') ?: $raw;
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('sm.php')]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Sm3::class, function () {
            return new Sm3();
        });

        $this->app->singleton(Sm4::class, function () {
            return new Sm4(config('sm.sm4_key'));
        });
    }
}
