<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Reservation;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PaymentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PaymentResource\RelationManagers;

class PaymentResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'My Home website';
    protected static ?string $modelLabel = 'Payments';


    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }
    public static function getEloquentQuery(): Builder
    {
        $query =  parent::getEloquentQuery()->where('payment_status', 'paid');

        if (Auth::check() && Auth::user()->type === 'seller') {
            $query->whereHas('estate', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        return $query ;
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#'),
                TextColumn::make('payment_details')
                    ->label('Card Type')
                    ->formatStateUsing(function ($state) {
                        $details = json_decode($state, true);
                        return $details['source_data_sub_type'] ?? '-';
                    }),
                TextColumn::make('user.name')->label('Buyer/Renter'),
                TextColumn::make('estate.title')->label('Estate Title'),
                TextColumn::make('estate.user.name')->label('Seller'),
                TextColumn::make('price')->label('Price')->suffix(' EGP'),
                TextColumn::make('updated_at')->label('Payment Date')->dateTime(),
            ])
            ->defaultSort('updated_at', 'desc')
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }


    public static function canCreate(): bool
    {
        return false;
    }
    public static function canEdit(Model $record): bool
    {
        return false;
    }
    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
