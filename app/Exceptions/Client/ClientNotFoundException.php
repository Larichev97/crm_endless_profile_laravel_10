<?php

namespace App\Exceptions\Client;

use Exception;

class ClientNotFoundException extends Exception
{
    protected $code = 404;
}
