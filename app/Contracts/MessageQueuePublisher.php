<?php

namespace App\Contracts;

interface MessageQueuePublisher
{
    public function publish(string $message, string $queue='');
}
