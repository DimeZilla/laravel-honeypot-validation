<?php

namespace DiamondHoneyPot\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Blade;
use DiamondHoneyPot\HoneyPot;
use DiamondHoneyPot\Facades\HoneyPot as HoneyPotFacade;

class HoneyPotProvider extends ServiceProvider
{

    private $failureMessage = 'Possible Spam Attack';

    /**
     * In this boot function, we're going to make sure that the
     * session key exists for us
     *
     * @return void
     */
    public function boot()
    {
        // create the session storage
        if (!session()->exists('honeypot_names')) {
            session()->put('honeypot_names', []);
        }

        // Publishing Config
        $this->publishes([
            __DIR__ . '/../config' => config_path()
        ], 'config');

        // Merge Config
        $this->mergeConfigFrom(__DIR__ . '/../config/honeypot.php', 'honeypot');

        if (!empty(config('honeypot.failureMessage'))) {
            $this->failureMessage = config('honeypot.failureMessage');
        }

        // add our validators
        Validator::extend('honeypot', function ($attribute, $value, $parameters, $validator) {
            return HoneyPotFacade::validateHoneyPot($attribute, $value);
        }, _($this->failureMessage));

        Validator::extend('honeypot_time', function ($attribute, $value, $parameters, $validator) {
            return HoneyPotFacade::validateHoneyPotTime($attribute, $value, $parameters);
        }, _($this->failureMessage));

        // adds a blade directive
        Blade::directive('honeypot', function ($expression) {
            $name = null;
            if (!empty($expression)) {
                $name = $expression;
            }
            return '<?php echo app(\'honeypot\')->make(' . $expression . '); ?>';
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
