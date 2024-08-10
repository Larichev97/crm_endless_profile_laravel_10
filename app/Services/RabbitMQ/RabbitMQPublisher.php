<?php

namespace App\Services\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQPublisher
{
    public function publishTelegramMessage(string $message): bool
    {
        if (!empty($message)) {
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
            $channel->queue_declare('telegram', false, true, false, false);

            $msg = new AMQPMessage($message);

            $channel->basic_publish($msg, '', 'telegram');
            $channel->close();

            $connection->close();

            return true;
        }

        return false;
    }
}
