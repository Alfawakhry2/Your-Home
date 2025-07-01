<?php

namespace App\Filament\Resources\EstateResource\Pages;

use App\Filament\Resources\EstateResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEstate extends CreateRecord
{
    protected static string $resource = EstateResource::class;

    //this as the event in the model
    protected function afterCreate(): void
    {
        foreach ($this->data['images'] as $key => $image) {
            # this->record mean the estate that created , this->record available with afterCreate
            $this->record->images()->create([
                'image' => $image,
                'is_main' => $key === 0,
            ]);
        }
    }


}
