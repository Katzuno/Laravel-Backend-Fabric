<?php

namespace App\Repositories;

use App\Models\Record;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class RecordRepository extends BaseRepository
{
    public function __construct(Record $model)
    {
        parent::__construct($model);
    }

    public function search(string $query): Collection
    {
        return Record::search($query)->get();
    }
}
