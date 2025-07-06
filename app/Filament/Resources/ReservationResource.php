<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Reservation;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'My Home website';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'rejected' => 'Rejected',
                    'completed' => 'Completed',
                ]),

                Select::make('payment_status')
                ->options([
                    'pending' => 'Pending',
                    'completed' => 'Completed',
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Buyer/Renter'),
                TextColumn::make('estate.user.name')
                    ->label('Seller/Owner'),
                TextColumn::make('estate.title')
                    ->label('Estate Title'),

                TextColumn::make('estate.status')
                    ->label('Estate Status')
                    ->badge()
                    ->color('success'),

                TextColumn::make('status')
                    ->label('Reservation Status')
                    ->badge()
                    ->color('info'),

                TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->color('danger'),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $estate_ids = auth()->user()->estates()->pluck('id')->toArray();
        if (Auth::user()->role == 'seller') {
            $query->whereIn('estate_id', $estate_ids);
        }
        return $query;
    }

    public static function canCreate(): bool
    {
        return false ;
    }
}
