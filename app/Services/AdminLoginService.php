<?php

namespace App\Services;

use Exception;
use App\Models\UserModel;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AdminLoginService implements ServiceInterface
{
    /**
     * Return null or Model
     * Param string of id $id
     */
    public function findById(string $id): ?Model
    {
        return null;
    }

    /**
     * Return null or Builder
     * Param array of $attributes
     */
    public function search(array $attributes): ?Builder
    {
        return UserModel::where($attributes)
            ->whereHas('user_type', function ($query) {
                $query->whereHas('type', function ($query) {
                    $query->typeAdmin();
                });
            });
    }

    /**
     * Return null or Model
     * Param array of $attributes
     */
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

    /**
     * Return null or Model
     * Param array of $attributes
     */
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
