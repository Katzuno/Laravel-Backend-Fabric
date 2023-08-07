<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    private function removeNullValues(array $attributes): array {
        foreach ($attributes as $key => $value) {
            if (empty($value)) {
                unset($attributes[$key]);
            }
        }

        return $attributes;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function update(array $attributes, int $id): Model
    {
        $model = $this->find($id);
        $currentAttributes = $model->getAttributes();
        $attributes = $this->removeNullValues($attributes);
        $attributes = array_merge($currentAttributes, $attributes);

        $model->update($attributes);
        return $model;
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }
}
