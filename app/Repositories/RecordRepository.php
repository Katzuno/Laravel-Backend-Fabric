<?php

namespace App\Repositories;

use App\Models\Record;

class RecordRepository extends BaseRepository
{
    public function __construct(Record $model)
    {
        parent::__construct($model);
    }
}
