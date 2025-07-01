<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Estate;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\EstateResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EstateResource\RelationManagers;

class EstateResource extends Resource
{
    protected static ?string $model = Estate::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('category_id')
                            ->relationship('category', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('title')
                            ->required()
                            ->maxLength(50)
                            ->columnSpan(1),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->columnSpan(1),

                        TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->prefix('EGP')
                            ->columnSpan(1),

                        Select::make('type')
                            ->options([
                                'sale' => 'Sale',
                                'rent' => 'Rent',
                            ])
                            ->required()
                            ->columnSpan(1),
                    ]),

                Section::make('Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('bedrooms')
                            ->numeric()
                            ->minValue(1)
                            ->required(),

                        TextInput::make('bathrooms')
                            ->numeric()
                            ->minValue(1)
                            ->required(),

                        TextInput::make('area')
                            ->numeric()
                            ->suffix('sq ft')
                            ->required(),

                        Select::make('status')
                            ->options([
                                'available' => 'Available',
                                'sold' => 'Sold',
                                'rented' => 'Rented'
                            ])
                            ->required(),
                    ]),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Main Image')
                            ->image()
                            ->directory('estates/main')
                            ->required()
                            ->columnSpan(1),


                        FileUpload::make('images')
                            ->label('Gallery Images')
                            ->image()
                            ->multiple()
                            ->directory('estates/gallery')
                            ->maxFiles(10)
                            ->appendFiles()
                            ->helperText('Maximum 10 images')
                            ->columnSpan(1),
                    ])
                    ->columns(2),

                Section::make('Description & Location')
                    ->schema([
                        MarkdownEditor::make('description')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('location')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('category.title')->label('Estate Type'),
                TextColumn::make('title')->label('Title')->sortable()->searchable(),
                TextColumn::make('description')->limit(50)->label('Description')->searchable(),
                TextColumn::make('status')->label('Estate Status')
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            'available' => 'success',
                            'sold' => 'danger',
                            'rented' => 'info',
                        };
                    })
                    ->searchable(),
                ImageColumn::make('image')->label('Image'),
            ])
            ->filters([
                SelectFilter::make('status')
                ->label('Estate Status')
                ->options(
                    [
                        'available' => 'Available',
                        'sold' => 'Sold',
                        'rented' => 'Rented',
                    ]
                ),
                SelectFilter::make('category_id')
                    ->label('Estate Type')
                    ->options(
                        Category::pluck('title', 'id')->toArray(),
                )

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEstates::route('/'),
            'create' => Pages\CreateEstate::route('/create'),
            'edit' => Pages\EditEstate::route('/{record}/edit'),
        ];
    }
}
