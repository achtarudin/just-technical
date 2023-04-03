<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewArticle extends ViewRecord
{
    protected static string $resource = ArticleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\RestoreAction::make(),
            Actions\DeleteAction::make(),

        ];
    }

    protected function getFormSchema(): array
    {
        return app(CreateArticle::class)->getFormSchema();
    }
}
