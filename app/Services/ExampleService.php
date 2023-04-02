<?php

namespace App\Services;

use Exception;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ExampleService implements ServiceInterface
{
    public function findById(string $id): ?Model
    {
        return null;
    }

    public function search(array $attributes): ?Builder
    {
        return null;
    }

    public function save(array $attributes): ?Model
    {
        DB::beginTransaction();
        try {
            DB::commit();
            return null;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update(Model $model, array $attributes): ?Model
    {
        DB::beginTransaction();
        try {
            DB::commit();
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
