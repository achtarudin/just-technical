<?php

namespace App\Services;

use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;

interface ServiceInterface
{
    public function findById(string $id): ?Model;

    public function search(array $attributes): ?Builder;

    public function save(array $attributes): ?Model;

    public function update(Model $model, array $attributes): ?Model;
}
