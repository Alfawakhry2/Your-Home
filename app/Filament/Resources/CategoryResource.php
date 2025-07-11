<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Filament\Resources\CategoryResource\RelationManagers\EstatesRelationManager;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'My Home website';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->rules('required|string|min:3|max:50')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($operation, $state, $set) {
                        $set('slug', Str::slug($state));
                    }),
                TextInput::make('slug')->required()->unique(ignoreRecord: true),
                MarkdownEditor::make('description')->required()->columnSpanFull(),
                FileUpload::make('image')->rules('required|image|mimes:png,jpg,jpeg,webp')->disk('public')->directory('categories'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('title')->label('Title')->sortable()->searchable()->badge(),
                TextColumn::make('description')->limit(50)->label('Description')->searchable(),
                ImageColumn::make('image')->label('Image'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            EstatesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('view any category');
    }
    public static function canView(Model $record): bool
    {
        return Auth::user()->can('view category');
    }

    public static function canCreate(): bool
    {
        return Auth::user()->can('create category');
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()->can('update category');
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()->can('delete category');
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()->can('delete category');
    }

}
