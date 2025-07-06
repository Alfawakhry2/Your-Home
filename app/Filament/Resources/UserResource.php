<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\EstatsRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    // protected static ?string $navigationGroup = 'My Home website';


    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->rules('required|string|min:3|max:50'),
                TextInput::make('email')->required()->email()->unique(ignoreRecord: true),
                TextInput::make('phone')->required(fn(string $context): bool => $context === 'create')->unique(ignoreRecord: true),
                Select::make('role')->options([
                    'admin' => 'Admin',
                    'seller' => 'Seller',
                    'buyer' => 'Buyer',
                ])->rules('required|in:admin,seller,buyer'),
                TextInput::make('password')->label('Password')
                    ->password()
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create')
                    ->maxLength(255)
                    ->same('password_confirmation'),
                TextInput::make('password_confirmation')->password(),

                FileUpload::make('image')->rules('required|image|mimes:png,jpg,jpeg')->disk('public')->directory('users'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')
                ->sortable()
                ->searchable()
                ,
                TextColumn::make('name')->label('Name')
                ->searchable(),
                TextColumn::make('email')->label("Email"),
                TextColumn::make('role')->badge()->color(function ($state) {
                    return match ($state) {
                        'admin' => 'danger',
                        'seller' => 'info',
                        'buyer' => 'success',
                    };
                })->sortable()
                ->searchable()
                ,
                ImageColumn::make('image')->label('Avatar')->circular(),
            ])
            ->filters([
                SelectFilter::make('role')
                ->label('Role')
                ->options([
                    'admin'=>'Admin',
                    'seller'=>'Seller',
                    'buyer' => 'Buyer',
                ])
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
            EstatsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()->role == 'admin';
    }

    // public static function canViewAny(): bool
    // {
    //     return Auth::user()->role == 'admin';
    // }


}
