<?php

namespace Deseco\Economic;

use Illuminate\Support\Facades\Facade as LaravelFacdee;

class Facade extends LaravelFacdee
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'economic';
    }
}
