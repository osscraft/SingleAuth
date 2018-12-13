<?php

namespace App\Providers;

use Lay\Advance\Session\MysqlSessionHandler;
use Illuminate\Support\Facades\Session;
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
        //
        Session::extend('samysql', function ($app) {
            // Return implementation of SessionHandlerInterface...

            var_dump($app);exit;
            return new MysqlSessionHandler();
        });
    }
}
