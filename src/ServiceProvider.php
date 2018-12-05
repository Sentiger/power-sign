<?php

namespace Sentiger\PowerSign;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(PowerSign::class, function () {
            return new PowerSign(config('services.power_sign.app_key'), config('services.power_sign.app_secret_key'), config('services.power_sign.expire_time'));
        });

        $this->app->alias(PowerSign::class, 'powerSign');
    }

    public function provides()
    {
        return [PowerSign::class, 'powerSign'];
    }
}