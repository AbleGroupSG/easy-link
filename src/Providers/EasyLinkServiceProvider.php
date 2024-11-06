<?php

namespace Serengiy\EasyLink\Providers;

use Illuminate\Support\ServiceProvider;

class EasyLinkServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/easy-link.php' => config_path('easy-link.php'),
        ]);
    }

    public function register()
    {
        //
    }
}
