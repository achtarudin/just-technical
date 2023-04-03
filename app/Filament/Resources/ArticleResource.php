<?php

namespace App\Filament\Resources;


use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\Article\ArticleModel;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\ArticleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ArticleResource\RelationManagers;

class ArticleResource extends Resource
{
    protected static ?string $slug = 'articles';

    protected static ?string $modelLabel = 'Articles';

    protected static ?string $model = ArticleModel::class;

    protected static ?string $navigationLabel = 'Articles';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->size('sm')->size(40)->circular()->grow(false),
                TextColumn::make('title')->size('sm')->grow(false),
                TextColumn::make('author.name')->size('sm'),
                TextColumn::make('created_at')->label('Created')->since()->size('sm'),
                TextColumn::make('updated_at')->label('Updated')->since()->size('sm'),
                TextColumn::make('deleted_at')->label('Deleted')->since()->size('sm')->color('danger')
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'view' => Pages\ViewArticle::route('/{record}'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])->whereUserId(auth()->user()->id);
    }

    public static function canView(Model $record): bool
    {
        return $record->user_id == auth()->user()->id;
    }

    public static function canEdit(Model $record): bool
    {
        return $record->user_id == auth()->user()->id && $record->trashed() == false;
    }

    public static function canDelete(Model $record): bool
    {
        return self::canEdit($record);
    }
}
