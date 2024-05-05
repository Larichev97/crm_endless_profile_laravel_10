<?php

namespace App\Services\Telegram\ContactFormMessagePipes;

use App\Models\ContactForm;
use Closure;

class ClientEmailPipe
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
            $email = $contactFormModel->email;

            if (!empty($email)) {
                $message .= "Email: ". $email."\n\n";
            }

            $data['message'] = $message;
        }

        return $next($data);
    }
}
