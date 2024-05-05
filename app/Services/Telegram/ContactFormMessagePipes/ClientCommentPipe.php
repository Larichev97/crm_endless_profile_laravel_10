<?php

namespace App\Services\Telegram\ContactFormMessagePipes;

use App\Models\ContactForm;
use Closure;

class ClientCommentPipe
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
            $comment = strip_tags(trim($contactFormModel->comment));

            if (!empty($comment)) {
                $message .= "Комментарий : ". $comment."\n\n";
            }

            $data['message'] = $message;
        }

        return $next($data);
    }
}
