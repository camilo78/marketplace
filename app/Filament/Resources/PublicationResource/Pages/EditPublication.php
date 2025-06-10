<?php

namespace App\Filament\Resources\PublicationResource\Pages;

use App\Filament\Resources\PublicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPublication extends EditRecord
{
    protected static string $resource = PublicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->updateCoverImage();
    }

    // Misma función de ayuda
    private function updateCoverImage(): void
    {
        $images = $this->record->images()->orderBy('order')->get();

        foreach ($images as $index => $image) {
            $image->is_cover = ($index === 0);
            $image->save();
        }
    }
}