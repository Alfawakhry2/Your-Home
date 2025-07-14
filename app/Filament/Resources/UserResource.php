<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Spatie\Permission\Models\Permission;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\CheckboxList;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\UserResource\Pages;
use Filament\Infolists\Components\Section as ComponentsSection;
use App\Filament\Resources\UserResource\RelationManagers\EstatsRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->label('Full Name')
                ->required()
                ->minLength(3),

            TextInput::make('email')
                ->label('Email')
                ->required()
                ->email()
                ->unique(ignoreRecord: true),

            TextInput::make('phone')
                ->label('Phone Number')
                ->required(fn(string $context) => $context === 'create')
                ->unique(ignoreRecord: true),

            Section::make('Role & Permissions')
                ->columns(2)
                ->schema([
                    //column named role_id (in role table)
                    Select::make('role_id')
                        ->label('Role')
                        //list the role table , take the id and name
                        ->options(fn() => Role::pluck('name', 'id'))
                        //required only in create form
                        ->required(fn(string $context) => $context === 'create')
                        //
                        ->afterStateHydrated(function (Set $set, $state, $record) {
                            if ($record && $record->roles()->exists()) {
                                $set('role_id', $record->roles()->first()->id);
                            }
                        })
                        ->reactive()
                        ->afterStateUpdated(function (?string $state, Set $set) {
                            $role = Role::find($state);
                            if ($role) {
                                $set('type', $role->name);

                                if ($role->name !== 'admin') {
                                    $set('permissions', $role->permissions->pluck('name')->toArray());
                                } else {
                                    $set('permissions', []);
                                }
                            }
                        })
                        ->dehydrated()
                        ->live(),

                    CheckboxList::make('permissions')
                        ->label('Permissions')
                        //we take name , name => cause deal with name and can display   and as avalue
                        ->options(fn() => Permission::pluck('name', 'name'))
                        ->columns(3)
                        ->afterStateHydrated(function (Set $set, ?Model $record) {
                            if ($record) {
                                $set('permissions', $record->getAllPermissions()->pluck('name')->toArray());
                            }
                        })
                        ->visible(function (Get $get) {
                            $roleId = $get('role_id');
                            if (!$roleId) return false;

                            $role = Role::find($roleId);
                            return $role && $role->name !== 'admin'; // Show only for non-admin
                        })
                        ->dehydrated(),
                ]),

            TextInput::make('type')
                ->hidden()
                ->dehydrated(),

            TextInput::make('password')
                ->label('Password')
                ->password()
                ->dehydrated(fn($state) => filled($state))
                ->required(fn(string $context) => $context === 'create')
                ->same('password_confirmation'),

            TextInput::make('password_confirmation')
                ->password(),

            FileUpload::make('image')
                ->label('Image')
                ->image()
                ->disk('public')
                ->directory('users')
                ->preserveFilenames()
                ->nullable()
                ->rules(['nullable', 'image', 'mimes:png,jpg,jpeg'])
                ->dehydrated(fn($state) => filled($state))
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('name')->label('Full Name')->searchable(),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('type')->label('Role')->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            'admin' => 'danger',
                            'co-admin' => 'primary',
                            'seller' => 'info',
                            'buyer' => 'success',
                        };
                    }),
                ImageColumn::make('image')->label('Image')->circular(),
            ])
            ->filters([])
            ->actions([

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ComponentsSection::make('Basic Info')
                    ->schema([
                        ImageEntry::make('image')->label('Profile Image')
                            ->height(40)
                            ->circular()
                            ->defaultImageUrl(asset('filament/default.png')),
                        TextEntry::make('name')->label('Full Name'),
                        TextEntry::make('email')->label('Email Address'),
                        TextEntry::make('type')->label('User Role')
                    ])->columns(2)
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

    public static function canViewAny(): bool
    {
        return Auth::user()?->can('view any user') ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->can('create user') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()?->can('update user') ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()?->can('delete user') && Auth::id() !== $record->id;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()?->can('delete any user') ?? false;
    }
}
