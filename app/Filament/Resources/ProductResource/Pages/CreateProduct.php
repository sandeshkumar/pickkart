<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Remove product_images from data as it's not a product column
        unset($data['product_images']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $images = $this->data['product_images'] ?? [];

        if (!empty($images)) {
            $sortOrder = 0;
            foreach (array_values($images) as $path) {
                $this->record->images()->create([
                    'path' => $path,
                    'alt_text' => $this->record->name,
                    'sort_order' => $sortOrder,
                    'is_primary' => $sortOrder === 0,
                ]);
                $sortOrder++;
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
