<?php

namespace App\Exceptions\Country;

use Exception;

class CountryNotFoundException extends Exception
{
    protected $code = 404;
}
