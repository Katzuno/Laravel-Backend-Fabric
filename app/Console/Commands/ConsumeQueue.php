<?php

namespace App\Console\Commands;

use App\Contracts\MessageQueueSubscriber;
use App\Services\RabbitMQSubscriber;
use App\Services\RecordService;
use Illuminate\Console\Command;

class ConsumeQueue extends Command
{
    protected $signature = 'queue:consume {queue}';
    protected $description = 'Consume messages from a queue';

    protected MessageQueueSubscriber $subscriber;
    protected RecordService $recordService;

    public function __construct(MessageQueueSubscriber $subscriber, RecordService $recordService)
    {
        parent::__construct();

        $this->subscriber = $subscriber;
        $this->recordService = $recordService;
    }
    public function handle(): void
    {
        $queue = $this->argument('queue');

        $this->info("Consuming messages from queue: {$queue}");

        $this->subscriber->subscribe($queue, function ($messageBody) {$message = json_decode($messageBody, true);

            if (!$message || !isset($message['data']['initialRecord']['id'])) {
                $this->error('Invalid message format. Missing identifier field in initialRecord.');
                return;
            }

            $initialRecord = $message['data']['initialRecord'];
            $enrichData = $message['data']['enrichData'];

            $record = $this->recordService->findRecordById($initialRecord['id']);

            if (!$record) {
                $this->info("No Record found with id: {$initialRecord['id']}");
                return;
            }

            if (!$record->imdb_id)  {
                if ($enrichData['imdbID'])  {
                    $record->imdb_id = $enrichData['imdbID'];
                    unset($enrichData['imdbID']);
                }
            }

            $this->info($enrichData['Poster'] . ' poster');
            if ($enrichData['Poster'])  {
                $record->images = $enrichData['Poster']; // update image with the original image
                unset($enrichData['Poster']);
            }

            $record->metadata = json_encode($enrichData);


            try {
                if ($this->recordService->saveRecord($record))  {
                    $this->info("Updated Record with imdb_id: {$record->id} with enriched metadata.");
                } else {
                    $this->info("Record {$record->id} not updated");
                }
            } catch (\Exception $exception) {
                $this->error("Record {$record->id} was not updated due to the following error: {$exception->getMessage()}");
            }
        });
    }
}
