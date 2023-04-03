<?php

namespace App\ServiceApiV2;

use App\Models\Article\ArticleModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class ApiV2AllArticelsService extends ApiV2ArticelService
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
            });
    }
}
