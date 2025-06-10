<?php

namespace App\Filament\Resources\PublicationResource\Pages;

use App\Filament\Resources\PublicationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePublication extends CreateRecord
{
    protected static string $resource = PublicationResource::class;

    protected function afterCreate(): void
    {
        $this->updateCoverImage();
    }

    // Función de ayuda para mantener el código limpio
    private function updateCoverImage(): void
    {
        $images = $this->record->images()->orderBy('order')->get();

        foreach ($images as $index => $image) {
            $image->is_cover = ($index === 0);
            $image->save();
        }
    }
}