<?php

namespace App\Providers;

use App\Contracts\MessageQueuePublisher;
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
                env('RABBITMQ_HOST'),
                env('RABBITMQ_PORT'),
                env('RABBITMQ_USER'),
                env('RABBITMQ_PASSWORD')
            );
        });

        $this->app->bind(MessageQueuePublisher::class, RabbitMQPublisher::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
