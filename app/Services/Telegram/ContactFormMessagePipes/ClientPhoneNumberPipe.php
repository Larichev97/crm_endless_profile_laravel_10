<?php

namespace App\Services\Telegram\ContactFormMessagePipes;

use App\Models\ContactForm;
use Closure;

class ClientPhoneNumberPipe
{
    /**
     * @param array $data
     * @param Closure $next
     * @return mixed
     */
    public function handle(array $data, Closure $next): mixed
    {
        $contactFormModel = $data['contact_form_object'];
        $message = $data['message'];

        if ($contactFormModel instanceof ContactForm && is_string($message)) {
            $phoneNumber = $contactFormModel->phone_number;

            if (!empty($phoneNumber)) {
                $message .= "Номер телефона: ". $phoneNumber."\n\n";
            }

            $data['message'] = $message;
        }

        return $next($data);
    }
}
