<?php

namespace App\ServiceApiV2;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Article\ArticleModel;
use App\ServiceApiV2\ApiV2Interface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ApiV2ArticelService implements ApiV2Interface
{
    public function findById(string $id): ?Model
    {
        return $this->search([])->whereId($id)->first();
    }

    public function search(array $attributes): ?Builder
    {
        return ArticleModel::query()
            ->when(count($attributes), function ($query) use ($attributes) {
                $query->where($attributes);
            })
            ->whereUserId(auth()->user()->id);
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
