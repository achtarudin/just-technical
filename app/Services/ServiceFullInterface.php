<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

interface ServiceFullInterface extends ServiceInterface
{
    public function delete(Model $model): ?Model;
}
