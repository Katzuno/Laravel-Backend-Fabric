<?php

namespace App\Providers;

use App\Contracts\MessageQueueSubscriber;
use App\Contracts\MessageQueuePublisher;
use App\Services\RabbitMQSubscriber;
use App\Services\RabbitMQPublisher;
use Illuminate\Support\ServiceProvider;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AMQPStreamConnection::class, function ($app) {
            return new AMQPStreamConnection(
                config('rabbitmq.host'),
                config('rabbitmq.port'),
                config('rabbitmq.user'),
                config('rabbitmq.password')
            );
        });

        $this->app->bind(MessageQueuePublisher::class, RabbitMQPublisher::class);
        $this->app->bind(MessageQueueSubscriber::class, RabbitMQSubscriber::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
