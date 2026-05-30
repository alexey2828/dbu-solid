<?php

namespace App\Repositories;

use App\Contracts\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    protected array $filterable = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function filter(array $filters): Collection
    {
        return $this->applyFilters($this->model->query(), $filters)->get();
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        foreach ($filters as $field => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            if (! empty($this->filterable) && ! in_array($field, $this->filterable, true)) {
                continue;
            }

            $query->where($field, $value);
        }

        return $query;
    }

    public function search(array $criteria): Collection
    {
        return $this->filter($criteria);
    }

    public function find(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $record = $this->find($id);
        $record->update($data);

        return $record;
    }

    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }
}
