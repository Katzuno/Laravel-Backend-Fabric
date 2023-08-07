<?php

namespace App\Services;

use App\Contracts\MessageQueuePublisher;
use App\Repositories\RecordRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

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

    public function getAllRecords(): Collection
    {
        return $this->repository->all();
    }

    public function createRecord(array $data): bool
    {
        try {
            $createdRecord = $this->repository->create($data);

            $this->queuePublisher->publish(
                json_encode(
                    array_merge(
                        ['pattern' => 'records_to_analyze'],
                        ['data' => $createdRecord]
                    )
                ),
                self::RECORDS_QUEUE
            );

            return true;
        } catch (\Exception $exception) {
            return false;
        }
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
        return $this->repository->update($data, $id);
    }

    public function saveRecord(Model $record): bool
    {
        return $record->save();
    }

    public function deleteRecord(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function searchRecords(string $query): Collection
    {
        return $this->repository->search($query);
    }
}
