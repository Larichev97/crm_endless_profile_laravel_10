<?php

namespace App\Services\Telegram\ContactFormMessagePipes;

use App\Models\ContactForm;
use Closure;

class TitlePipe
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
            $contactFormId = (int) $contactFormModel->getKey();

            $message .= "\n<b><a href=\"".route('admin.contact-forms.show', $contactFormId)."\">Новая заявка #".$contactFormId."</a></b>\n\n";

            $data['message'] = $message;
        }

        return $next($data);
    }
}
