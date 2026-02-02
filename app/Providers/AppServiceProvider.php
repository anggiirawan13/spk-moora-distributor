<?php

// app/Providers/AppServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Override public path untuk DomPDF / production
        if (App::environment('production')) {
            $this->app->bind('path.public', function() {
                return base_path() . '/../public_html/spk.anugrahhadi.com';
            });
        }
    }
}
