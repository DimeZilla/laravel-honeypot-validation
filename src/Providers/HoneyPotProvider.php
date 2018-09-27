<?php

namespace DiamondHoneyPot\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Blade;
use DiamondHoneyPot\HoneyPot;
use DiamondHoneyPot\Facades\HoneyPot as HoneyPotFacade;

class HoneyPotProvider extends ServiceProvider
{

    /**
     * In this boot function, we're going to make sure that the
     * session key exists for us
     *
     * @return void
     */
    public function boot()
    {
        if (!session()->exists('honeypot_names')) {
            session()->put('honeypot_names', []);
        }

        // add our validators
        Validator::extend('honeypot', function ($attribute, $value, $parameters, $validator) {
            return HoneyPotFacade::validateHoneyPot($attribute, $value);
        }, 'Possible Spam Input.');

        Validator::extend('honeypot_time', function ($attribute, $value, $parameters, $validator) {
            return HoneyPotFacade::validateHoneyPotTime($attribute, $value, $parameters);
        }, 'Possible Spam Input.');

        // adds a blade directive
        Blade::directive('honeypot', function ($expression) {
            $name = null;
            if (!empty($expression)) {
                $name = $expression;
            }

            return HoneyPotFacade::make($expression);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('honeypot', function ($app) {
            return new HoneyPot($app);
        });
    }
}
