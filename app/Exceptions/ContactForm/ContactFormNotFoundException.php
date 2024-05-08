<?php

namespace App\Exceptions\ContactForm;

use Exception;

class ContactFormNotFoundException extends Exception
{
    protected $code = 404;
}
