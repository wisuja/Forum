<?php

namespace App\Providers;

use App\Models\Channel;
use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(DebugbarServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        
        View::composer('*', function ($view) {
            $channels = Cache::rememberForever('channels', function () {
                return Channel::all();
            });
            $view->with('channels', $channels);
        });

        Validator::extend('spamfree', '\App\Rules\SpamFree@passes');
        Validator::extend('recaptcha', '\App\Rules\Recaptcha@passes');
    }
}
