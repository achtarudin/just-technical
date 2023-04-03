<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ArticleResource;
use App\Filament\Resources\ArticleResource\Pages\CreateArticle;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $result = array_merge($data, [
            'user_id'    => auth()->user()->id,
            'updated_at' => now()
        ]);
        return $result;
    }

    protected function getFormSchema(): array
    {
        return app(CreateArticle::class)->getFormSchema();
    }
}
