<?php

namespace DiamondHoneyPot\Facades;

use Illuminate\Support\Facades\Facade;

class HoneyPot extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'honeypot';
    }
}
