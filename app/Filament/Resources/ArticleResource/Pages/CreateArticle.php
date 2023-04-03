<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use Filament\Pages\Actions;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ArticleResource;
use App\Models\Article\ArticleModel;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected static bool $canCreateAnother = false;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $result = array_merge($data, [
            'user_id'    => auth()->user()->id,
            'created_at' => now()
        ]);
        return $result;
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make()->columns('2')->schema([
                FileUpload::make('image')
                    ->maxSize(512)
                    ->image()
                    ->required()
                    ->label('Banner'),

                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->unique(table: ArticleModel::class, ignoreRecord: true)
                    ->rules([
                        'required',
                        'string',
                        'min:4',
                        "unique:articles,title,except,id"
                    ]),

                RichEditor::make('content')
                    ->disableAllToolbarButtons()
                    ->enableToolbarButtons([
                        'bold',
                        'italic',
                        'strike',
                        'h2',
                        'h3',
                    ])
                    ->rules([
                        'required',
                        'min:10',
                    ])
                    ->columnSpan('full')
            ])
        ];
    }
}
