<?php

namespace App\Services\Telegram\ContactFormMessagePipes;

use App\Models\ContactForm;
use Closure;

class ClientNamePipe
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
            $firstName = $contactFormModel->firstname;
            $lastName = $contactFormModel->lastname;

            if (!empty($firstName)) {
                $message .= "Имя : ". $firstName."\n\n";
            }

            if (!empty($lastName)) {
                $message .= "Фамилия : ". $lastName."\n\n";
            }

            $data['message'] = $message;
        }

        return $next($data);
    }
}
