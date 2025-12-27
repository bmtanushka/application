<?php

namespace App\Providers;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url) {

        //[growcrm] disable debug bar in production mode
        if (!env('APP_DEBUG_TOOLBAR')) {
            \Debugbar::disable();
        }

        //[growcrm] force SSL rul's
        if (env('ENFORCE_SSL', false)) {
            $url->forceScheme('https');
        }

        //[growcrm] - use bootstrap css for paginator
        Paginator::useBootstrap();

        //[growcrm]
        $this->app->bind(Carbon::class, function (Container $container) {
            return new Carbon('now', 'Europe/Brussels');
        });
        
        define('SP_CLIENTS', [
            9,4,
        ]);
        
        define('SP_CLIENT_USERS', [
            22,13,37,38,
        ]);
        
        define('SP_CLIENT_PROJECT', [
            '22' => '169',
            '13' => '202',
            '37' => '202',
            '38' => '169',
        ]);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }
}
