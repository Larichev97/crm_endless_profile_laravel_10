<?php

namespace App\Exceptions\Setting;

use Exception;

class SettingNotFoundException extends Exception
{
    protected $code = 404;
}
