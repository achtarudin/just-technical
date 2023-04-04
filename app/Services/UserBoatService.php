<?php

namespace App\Services;

use Exception;
use App\Services\UploadImageAble;
use Illuminate\Http\UploadedFile;
use App\Exceptions\ApiV1Exception;
use App\Models\Boat\UserBoatModel;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserBoatService implements ServiceInterface
{
    use UploadImageAble;

    public function findById(string $id): ?Model
    {
        return UserBoatModel::where([
            'id' => $id,
            'user_id' => auth()->user()->id,
        ])->first();
    }

    public function search(array $attributes = []): ?Builder
    {
        return UserBoatModel::where('user_id', auth()->user()->id);
    }

    public function save(array $attributes): ?Model
    {
        DB::beginTransaction();
        try {
            $dataBoat = collect($attributes)
                ->except(['document', 'image'])->toArray();

            $userBoat = UserBoatModel::create(array_merge($dataBoat, [
                'user_id'       => auth()->user()->id,
                'created_at'    => now()
            ]));

            /**
             * Upload the image boat
             */
            $image = null;
            if (filled($attributes['image'] ?? null) && $attributes['image'] instanceof UploadedFile) {
                $image = $this->uploadImageOrPdf($attributes['image']);
            }

            /**
             * Upload the document boat
             */
            $document = null;
            if (filled($attributes['document'] ?? null) && $attributes['document'] instanceof UploadedFile) {
                $document = $this->uploadImageOrPdf($attributes['document']);
            }

            $userBoat->image  = $image;
            $userBoat->document  = $document;
            $userBoat->save();
            DB::commit();
            return $userBoat;
        } catch (Exception $e) {
            DB::rollBack();
            throw_if(true, new ApiV1Exception('Error When create boat', 500));
        }
    }

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

            $userBoat->image  = $image;
            $userBoat->document  = $document;
            $userBoat->save();
            DB::commit();
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            throw_if(true, new ApiV1Exception('Error When update boat', 500));
        }
    }
}
