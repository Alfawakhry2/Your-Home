<?php

namespace App\Filament\Resources\EstateResource\Pages;

use App\Filament\Resources\EstateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEstate extends EditRecord
{
    protected static string $resource = EstateResource::class;

    //this as the event in the model
    protected function afterSave(): void
    {

        foreach ($this->data['images'] as $key => $image) {
            $this->record->images()->create([
                'image' => $image,
                //if i need use this , and not add main => $key == 0 , the first image only will true and all false
                'is_main' => false,
                // 'is_main' => $key == 0,
            ]);
        }

    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
