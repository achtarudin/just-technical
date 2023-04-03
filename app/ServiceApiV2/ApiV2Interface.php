<?php

namespace App\ServiceApiV2;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;

interface ApiV2Interface
{
    public function findById(string $id): ?Model;

    public function search(array $attributes): ?Builder;

    public function save(array $attributes): ?Model;

    public function update(Model $model, array $attributes): ?Model;
}