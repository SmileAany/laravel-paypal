<?php

namespace Smbear\Paypal\Providers;

use Smbear\Paypal\Paypal;
use Illuminate\Support\ServiceProvider;

class PaypalServiceProvider extends ServiceProvider
{
    public function boot()
    {
         $this->publishes([
             __DIR__.'/../../config/paypal.php' => config_path('paypal.php'),
         ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
             __DIR__.'/../../config/paypal.php', 'paypal'
        );

        $this->app->singleton('paypal',function (){
            return new Paypal();
        });
    }
}
