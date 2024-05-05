<?php

namespace App\Services\Telegram\ContactFormMessagePipes;

use App\Models\ContactForm;
use Carbon\Carbon;
use Closure;

class DateCreatePipe
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
            $createdAt = $contactFormModel->created_at;

            if (!empty($createdAt)) {
                $message .= "Дата создания: ". Carbon::parse($createdAt)->format('d.m.Y H:i:s');
            }

            $data['message'] = $message;
        }

        return $next($data);
    }
}
