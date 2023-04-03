<?php

namespace App\ServiceApiV2;

use Exception;
use Illuminate\Http\UploadedFile;
use App\Exceptions\ApiV2Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Article\ArticleModel;
use App\ServiceApiV2\ApiV2Interface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ApiV2ArticelService implements ApiV2Interface
{
    use ApiV2UploadImageAble;

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

            /**
             * Filter the attributes
             */
            $dataArticle = collect($attributes)->only(['title', 'content'])->toArray();

            /**
             * Create an article
             */
            $article = ArticleModel::create(array_merge($dataArticle, [
                'user_id' => auth()->user()->id,
                'created_at' => now()
            ]));

            /**
             * Upload the image
             */
            $imageFile = null;
            if (filled($attributes['image'] ?? null) && $attributes['image'] instanceof UploadedFile) {
                $fileName = $this->uploadImageArticle($attributes['image']);
                $imageFile = $fileName;
            }

            $article->image  = $imageFile;

            $article->save();

            DB::commit();
            return $article;
        } catch (Exception $e) {
            DB::rollBack();
            throw_if(true, new ApiV2Exception('Failed Create Article', 500));
        }
    }

    public function update(Model $model, array $attributes): ?Model
    {
        DB::beginTransaction();
        try {

            /**
             * Filter the attributes
             */
            $dataArticle = collect($attributes)->only(['title', 'content'])->toArray();

            /**
             * Update an article
             */
            $model->update(array_merge($dataArticle, [
                'updated_at' => now()
            ]));

            /**
             * Upload the new image and delete old image when new image is exists
             */
            $imageFile = $model->image;
            if (filled($attributes['image'] ?? null) && $attributes['image'] instanceof UploadedFile) {
                $fileName = $this->uploadImageArticle($attributes['image'], $model->image);
                $imageFile = $fileName;
            }

            $model->image  = $imageFile;

            DB::commit();
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            throw_if(true, new ApiV2Exception('Failed Update Article', 500));
        }
    }

    public function delete(Model $model): ?Model
    {
        DB::beginTransaction();
        try {
            $model->delete();
            DB::commit();
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            throw_if(true, new ApiV2Exception('Failed Delete Article', 500));
        }
    }
}
