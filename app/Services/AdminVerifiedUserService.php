<?php

namespace App\Services;

use Exception;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Otp\OtpRegistrationModel;
use Illuminate\Database\Eloquent\Builder;

class AdminVerifiedUserService implements ServiceInterface
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
    public function search(array $attributes = []): ?Builder
    {
        return OtpRegistrationModel::query()
            ->when(count($attributes), function ($query) use ($attributes) {
                $query->where($attributes);
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
            $model->update(array_merge(
                $attributes,
                ['updated_at' => now(), 'admin_id' => auth()->user()->id]
            ));
            DB::commit();
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
