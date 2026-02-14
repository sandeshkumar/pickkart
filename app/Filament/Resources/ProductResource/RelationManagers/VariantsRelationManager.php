<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected function getOptionLabels(): array
    {
        $options = $this->getOwnerRecord()->options ?? [];
        $labels = [];
        foreach ($options as $i => $option) {
            $labels['option' . ($i + 1)] = $option['name'] ?? ('Option ' . ($i + 1));
        }
        return $labels;
    }

    public function form(Form $form): Form
    {
        $labels = $this->getOptionLabels();

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->maxLength(100),

                Forms\Components\TextInput::make('option1')
                    ->label($labels['option1'] ?? 'Option 1')
                    ->maxLength(100)
                    ->visible(fn () => isset($labels['option1'])),

                Forms\Components\TextInput::make('option2')
                    ->label($labels['option2'] ?? 'Option 2')
                    ->maxLength(100)
                    ->visible(fn () => isset($labels['option2'])),

                Forms\Components\TextInput::make('option3')
                    ->label($labels['option3'] ?? 'Option 3')
                    ->maxLength(100)
                    ->visible(fn () => isset($labels['option3'])),

                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix(currency_symbol())
                    ->minValue(0)
                    ->step(0.01),

                Forms\Components\TextInput::make('compare_at_price')
                    ->label('Compare Price')
                    ->numeric()
                    ->prefix(currency_symbol())
                    ->minValue(0)
                    ->step(0.01),

                Forms\Components\TextInput::make('stock_quantity')
                    ->label('Stock')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),

                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        $labels = $this->getOptionLabels();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),

                Tables\Columns\TextColumn::make('option1')
                    ->label($labels['option1'] ?? 'Option 1')
                    ->visible(fn () => isset($labels['option1'])),

                Tables\Columns\TextColumn::make('option2')
                    ->label($labels['option2'] ?? 'Option 2')
                    ->visible(fn () => isset($labels['option2'])),

                Tables\Columns\TextColumn::make('option3')
                    ->label($labels['option3'] ?? 'Option 3')
                    ->visible(fn () => isset($labels['option3'])),

                Tables\Columns\TextColumn::make('price')
                    ->money(currency_code())
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->sortable()
                    ->color(fn ($state): string => $state <= 0 ? 'danger' : ($state <= 5 ? 'warning' : 'success')),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([])
            ->headerActions([
                Tables\Actions\Action::make('generate')
                    ->label('Generate Variants')
                    ->icon('heroicon-o-sparkles')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Generate Variant Combinations')
                    ->modalDescription('This will create variants for all option combinations that don\'t already exist. Existing variants will not be affected.')
                    ->action(function () {
                        $product = $this->getOwnerRecord();
                        $options = $product->options ?? [];

                        if (empty($options)) {
                            Notification::make()
                                ->title('No options defined')
                                ->body('Please add options on the "Options & Variants" tab first, then save the product.')
                                ->warning()
                                ->send();
                            return;
                        }

                        $valueSets = [];
                        foreach ($options as $option) {
                            $valueSets[] = $option['values'] ?? [];
                        }

                        // Generate cartesian product of all option values
                        $combinations = [[]];
                        foreach ($valueSets as $values) {
                            $newCombinations = [];
                            foreach ($combinations as $combo) {
                                foreach ($values as $value) {
                                    $newCombinations[] = array_merge($combo, [$value]);
                                }
                            }
                            $combinations = $newCombinations;
                        }

                        $created = 0;
                        foreach ($combinations as $i => $combo) {
                            $option1 = $combo[0] ?? null;
                            $option2 = $combo[1] ?? null;
                            $option3 = $combo[2] ?? null;

                            // Skip if variant already exists
                            $exists = $product->variants()
                                ->where('option1', $option1)
                                ->where('option2', $option2)
                                ->where('option3', $option3)
                                ->exists();

                            if (!$exists) {
                                $name = collect($combo)->filter()->implode(' / ');
                                $product->variants()->create([
                                    'name' => $name,
                                    'option1' => $option1,
                                    'option2' => $option2,
                                    'option3' => $option3,
                                    'price' => $product->price,
                                    'stock_quantity' => 0,
                                    'is_active' => true,
                                    'sort_order' => $i,
                                ]);
                                $created++;
                            }
                        }

                        Notification::make()
                            ->title($created > 0 ? "{$created} variants generated" : 'No new variants needed')
                            ->body($created > 0 ? 'All option combinations have been created.' : 'All combinations already exist.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
