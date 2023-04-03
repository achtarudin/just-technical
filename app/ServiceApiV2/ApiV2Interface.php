<?php

namespace App\ServiceApiV2;

use App\Services\ServiceInterface;
use Illuminate\Database\Eloquent\Model;

interface ApiV2Interface extends ServiceInterface
{
    public function delete(Model $model): ?Model;
}
