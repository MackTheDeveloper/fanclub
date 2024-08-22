<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('StrToArrLen', function ($attribute, $value, $parameters, $validator)
        {
            // put keywords into array
            $keywords = explode(',', $value);

            if(count($keywords) > 2)
            {
                return false;
            }

            return true;
        });
    }
}
