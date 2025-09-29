<?php

namespace App\Architecture\Repositories\Classes;

use App\Models\Setting;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

abstract class AbstractRepository
{
    protected int $perPage;
    public function __construct(
        public Model $model
    )
    {
//        $this->perPage = Cache::remember(
//            'settings.pagination_limits',
//            604800,
//            function () {
//                $value = Setting::where('key', 'pagination_limits')->value('value');
//                return is_numeric($value) ? (int) $value : 10;
//            }
//        );
    }

    public function prepareQuery(): Builder
    {
        return $this->model->query();
    }

    public function getAll(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->prepareQuery()->with($relations)->get($columns);
    }

    public function getActive(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->prepareQuery()->with($relations)->where('is_active', true)->get($columns);
    }

    public function getById(
        $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model {
        return $this->prepareQuery()->select($columns)->with($relations)->findOrFail($modelId)->append($appends);
    }

    public function get(
        $byColumn,
        $value,
        array $columns = ['*'],
        array $relations = [],
    ): array|Collection {
        return $this->prepareQuery()::query()->select($columns)->with($relations)->where($byColumn, $value)->get();
    }

    public function first(
        $byColumn,
        $value,
        array $columns = ['*'],
        array $relations = [],
    ): Builder|Model|null {
        return $this->prepareQuery()::query()->select($columns)->with($relations)->where($byColumn, $value)->first();
    }

    public function getFirst(): ?Model
    {
        return $this->prepareQuery()->first();
    }

    public function create(array $payload): ?Model
    {
        $model = $this->prepareQuery()->create($payload);

        return $model->fresh();
    }

    public function insert(array $payload): bool
    {
        $model = $this->prepareQuery()->insert($payload);

        return $model;
    }

    public function createMany(array $payload): bool
    {
        try {
            foreach ($payload as $record) {
                $this->prepareQuery()->create($record);
            }
            return true;
        } catch (Exception $e) {
            Log::error('CATCH: '. $e);
            return false;
        }
    }

    public function update($modelId, array $payload): bool
    {
        $model = $this->getById($modelId);

        return $model->update($payload);
    }

    public function delete($modelId, array $filesFields = []): bool
    {
        $model = $this->getById($modelId);
        foreach ($filesFields as $field) {
            if ($model->$field !== null) {
                $this->deleteFile($model->$field);
            }
        }
        return $model->delete();
    }

    public function forceDelete($modelId, array $filesFields = []): bool
    {
        $model = $this->getById($modelId);
        foreach ($filesFields as $field) {
            if ($model->$field !== null) {
                $this->deleteFile($model->$field);
            }
        }
        return $model->forceDelete();
    }

    public function paginate(int $perPage = 10, array $relations = [], $orderBy = 'ASC', $columns = ['*'])
    {
        return $this->model::query()->select($columns)->with($relations)->orderBy('id', $orderBy)->paginate($perPage);
    }

    public function paginateWithQuery(
        $query,
        int $perPage = 10,
        array $relations = [],
        $orderBy = 'ASC',
        $columns = ['*'],
    ) {
        return  $this->prepareQuery()->select($columns)->where($query)->with($relations)->orderBy('id', $orderBy)->paginate($perPage);
    }


    public function whereHasMorph($relation, $class)
    {
        return $this->prepareQuery()->whereHasMorph($relation, $class)->get();
    }
}
