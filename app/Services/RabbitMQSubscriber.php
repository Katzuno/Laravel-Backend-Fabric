<?php

namespace App\Services;

use App\Contracts\MessageQueueSubscriber;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQSubscriber implements MessageQueueSubscriber
{
    private AMQPStreamConnection $connection;
    private AbstractChannel|AMQPChannel $channel;

    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
        $this->channel = $this->connection->channel();
    }

    public function subscribe(string $queue, callable $callback): void
    {
        $this->channel->queue_declare($queue, false, true, false, false);

        $this->channel->basic_consume($queue, '', false, true, false, false,
            function (AMQPMessage $message) use ($callback) {
                $callback($message->body);
             });

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }

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
