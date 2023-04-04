<?php

namespace App\Services;

use Exception;
use App\Services\UploadImageAble;
use App\Exceptions\ApiV1Exception;
use App\Models\Boat\UserBoatModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AdminVerifiedBoatService implements ServiceFullInterface
{
    use UploadImageAble;

    /**
     * Return null or Model
     * Param string of id $id
     */
    public function findById(string $id): ?Model
    {
        return UserBoatModel::whereId($id)->first();
    }

    /**
     * Return null or Builder
     * Param array of $attributes
     */
    public function search(array $attributes = []): ?Builder
    {
        try {
            return UserBoatModel::query()
                ->when(count($attributes), function ($query) use ($attributes) {
                    $query->where($attributes);
                });
        } catch (Exception $th) {
            throw_if(true, new ApiV1Exception('Error when search verified boat', 500));
        }
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
            throw_if(true, new ApiV1Exception('Error when create verified boat', 500));
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
            $userBoat = $model;

            $dataBoat = collect($attributes)
                ->except(['document', 'image'])->toArray();

            $userBoat->update(array_merge($dataBoat, [
                'updated_at'    => now()
            ]));

            /**
             * Upload the image boat
             */
            $image = $userBoat->image;
            if (filled($attributes['image'] ?? null) && $attributes['image'] instanceof UploadedFile) {
                $image = $this->uploadImageOrPdf($attributes['image'], $userBoat->image);
            }

            /**
             * Upload the document boat
             */
            $document = $userBoat->document;
            if (filled($attributes['document'] ?? null) && $attributes['document'] instanceof UploadedFile) {
                $document = $this->uploadImageOrPdf($attributes['document'], $userBoat->document);
            }
            $userBoat->image     = $image;
            $userBoat->document  = $document;
            $userBoat->admin_id  = auth()->user()->id;
            $userBoat->save();
            DB::commit();
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            throw_if(true, new ApiV1Exception('Error when update verified boat', 500));
        }
    }

    public function delete(Model $model): ?Model
    {
        DB::beginTransaction();
        try {
            $model->admin_id  = auth()->user()->id;
            $model->save();
            $model->delete();
            DB::commit();
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            throw_if(true, new ApiV1Exception('Error when delete verified boat', 500));
        }
    }
}
