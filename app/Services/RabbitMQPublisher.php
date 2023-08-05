<?php

namespace App\Services;

use App\Contracts\MessageQueuePublisher;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQPublisher implements MessageQueuePublisher
{
    private AMQPStreamConnection $connection;
    private AbstractChannel|AMQPChannel $channel;

    /**
     * @throws \Exception
     */
    public function __construct(AMQPStreamConnection $connection, string $queue = '')
    {
        $this->connection = $connection;
        $this->channel = $this->connection->channel();
    }

    public function publish(string $message, string $queue=''): void
    {
        $this->channel->queue_declare($queue, false, true, false, false);

        $amqpMessage = new AMQPMessage($message, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

        $this->channel->basic_publish($amqpMessage, '', $queue);

        $this->channel->close();
    }

    /**
     * @throws \Exception
     */
    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
