<?php

namespace App\Services;

use App\Contracts\MessageQueuePublisher;
use App\Repositories\RecordRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RecordService
{
    const RECORDS_QUEUE = 'records_to_analyze';

    protected RecordRepository $repository;

    private MessageQueuePublisher $queuePublisher;

    public function __construct(RecordRepository $repository, MessageQueuePublisher $messageQueuePublisher)
    {
        $this->repository = $repository;
        $this->queuePublisher = $messageQueuePublisher;
    }

    public function getAllRecords(): \Illuminate\Support\Collection
    {
        return $this->repository->all();
    }

    /**
     * @throws \Exception
     */
    public function createRecord(array $data): Model
    {
        $this->queuePublisher->publish(
            json_encode(
                array_merge([
                    'event_name' => 'NewRecord',
                ], $data)
            ),
            self::RECORDS_QUEUE
        );

        return $this->repository->create($data);
    }

    public function findRecordById(int $id): ?Model
    {
        $record = $this->repository->find($id);

        if (!$record) {
            throw new ModelNotFoundException('Record not found');
        }

        return $record;
    }

    public function updateRecord(int $id, array $data): Model
    {
        $record = $this->findRecordById($id);
        $record->update($data);

        return $record;
    }

    public function deleteRecord(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
