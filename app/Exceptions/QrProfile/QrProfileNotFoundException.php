<?php

namespace App\Exceptions\QrProfile;

use Exception;

class QrProfileNotFoundException extends Exception
{
    protected $code = 404;
}
