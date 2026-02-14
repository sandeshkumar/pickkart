<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Order Items';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Product')
                    ->searchable(),

                Tables\Columns\TextColumn::make('variant_name')
                    ->label('Variant')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('quantity')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('unit_price')
                    ->label('Unit Price')
                    ->money(currency_code()),

                Tables\Columns\TextColumn::make('total')
                    ->money(currency_code())
                    ->weight('bold'),
            ])
            ->paginated(false);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
