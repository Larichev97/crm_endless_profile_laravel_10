<?php

namespace App\Console\Commands;

use App\Models\ContactForm;
use App\Repositories\ContactForm\ContactFormRepository;
use App\Services\CrudActionsServices\ContactFormService;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ConsumeTelegramMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume-telegram-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /**
         *   For local Devilbox `rabbit` docker container:
         *
         *  `host` => '172.16.238.210'
         *  `port` => 5672
         *  `user` => 'guest'
         *  `passwort` => 'guest'
         *  `vhost` => 'my_vhost'
         *
         */
        $connection = new AMQPStreamConnection('172.16.238.210', 5672, 'guest', 'guest', 'my_vhost');

        $channel = $connection->channel();

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function ($msg) {
            if (is_numeric($msg->body) && (int) $msg->body > 0) {
                $contactFormService = new ContactFormService();
                $contactFormRepository = new ContactFormRepository();

                $contactFormModel = $contactFormRepository->getForEditModel((int) $msg->body, true);

                if (!empty($contactFormModel) && $contactFormModel instanceof ContactForm) {
                    $contactFormService->processSendContactFormInTelegramChannel($contactFormModel);
                }
            }

            echo ' [x] Received ID Contact Form = '. $msg->body.' (sending message about new Contact in Telegram group) '. "\n";
        };

        $channel->basic_consume('telegram', '', false, true, false, false, $callback);

        try {
            $channel->consume();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }
    }
}
