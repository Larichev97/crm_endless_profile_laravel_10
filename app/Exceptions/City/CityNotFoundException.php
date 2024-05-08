<?php

namespace App\Exceptions\City;

use Exception;

class CityNotFoundException extends Exception
{
    protected $code = 404;
}
