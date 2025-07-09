<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Estate;
use Filament\Forms\Set;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Resources\EstateResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EstateResource\RelationManagers;
use App\Filament\Resources\EstateResource\RelationManagers\UserRelationManager;

class EstateResource extends Resource
{
    protected static ?string $model = Estate::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'My Home website';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        $userRole = Auth::user()->role ;

        return $form
            ->schema([
                Section::make('Basic Information')
                    ->columns(2)
                    ->schema([
                        $userRole === 'admin'
                            ? Select::make('user_id')
                            ->label('Owner')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            : Hidden::make('user_id')
                            ->default(Auth::id()),


                        Select::make('category_id')
                            ->relationship('category', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('title')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($operation, $state, Set $set) {
                                ## opertaion tell you that in edit or create form
                                // if($operation === 'edit') return;
                                $set('slug', Str::slug($state));
                            })
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
                            // ->minValue(1)
                            ->required(),

                        TextInput::make('bathrooms')
                            ->numeric()
                            // ->minValue(1)
                            ->required(),

                        TextInput::make('area')
                            ->numeric()
                            ->suffix('Meter')
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
                TextColumn::make('title')->label('Title')->sortable()->searchable(),
                TextColumn::make('category.title')->label('Estate Type')->badge()->searchable()
                    ->color('gray'),

                TextColumn::make('user.name')->label('Owner')->badge()->color('warning'),
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
            UserRelationManager::class,
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()->role == 'seller') {
            $query->where('user_id', Auth::id());
        }
        return $query;
    }

    # filamant when create if there are hidden , may not send with form , and to confirm , you should send it after form created
    # with mutatedFormDataBeforeCreate function 
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->role === 'seller') {
            $data['user_id'] = auth()->id();
        }
        return $data;
    }
}
