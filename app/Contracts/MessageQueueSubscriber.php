<?php

namespace App\Contracts;

interface MessageQueueSubscriber
{
    public function subscribe(string $queue, callable $callback);
}
