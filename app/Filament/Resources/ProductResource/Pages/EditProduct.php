<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load existing images into the form field
        $data['product_images'] = $this->record->images()
            ->orderBy('sort_order')
            ->pluck('path')
            ->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['product_images']);

        return $data;
    }

    protected function afterSave(): void
    {
        $uploadedImages = array_values($this->data['product_images'] ?? []);
        $existingImages = $this->record->images()->orderBy('sort_order')->get();

        // Delete images that were removed
        foreach ($existingImages as $image) {
            if (!in_array($image->path, $uploadedImages)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }

        // Add new images and update sort order
        foreach ($uploadedImages as $sortOrder => $path) {
            $existing = $this->record->images()->where('path', $path)->first();

            if ($existing) {
                $existing->update([
                    'sort_order' => $sortOrder,
                    'is_primary' => $sortOrder === 0,
                ]);
            } else {
                $this->record->images()->create([
                    'path' => $path,
                    'alt_text' => $this->record->name,
                    'sort_order' => $sortOrder,
                    'is_primary' => $sortOrder === 0,
                ]);
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
