<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Product')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Basic Information')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state))),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                Forms\Components\TextInput::make('sku')
                                    ->label('SKU')
                                    ->maxLength(100)
                                    ->unique(ignoreRecord: true),

                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\Select::make('brand_id')
                                    ->relationship('brand', 'name')
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\Select::make('seller_id')
                                    ->relationship('seller', 'name')
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\Textarea::make('short_description')
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->columnSpanFull(),

                                Forms\Components\RichEditor::make('description')
                                    ->columnSpanFull(),

                                Forms\Components\Select::make('tags')
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),

                        Forms\Components\Tabs\Tab::make('Pricing')
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->required()
                                    ->numeric()
                                    ->prefix(currency_symbol())
                                    ->minValue(0)
                                    ->step(0.01),

                                Forms\Components\TextInput::make('compare_at_price')
                                    ->label('Compare at Price')
                                    ->numeric()
                                    ->prefix(currency_symbol())
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->helperText('Original price before discount. Must be higher than price.'),

                                Forms\Components\TextInput::make('cost_price')
                                    ->label('Cost Price')
                                    ->numeric()
                                    ->prefix(currency_symbol())
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->helperText('Your cost for this product. Not visible to customers.'),
                            ])
                            ->columns(3),

                        Forms\Components\Tabs\Tab::make('Inventory')
                            ->schema([
                                Forms\Components\TextInput::make('stock_quantity')
                                    ->label('Stock Quantity')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),

                                Forms\Components\TextInput::make('low_stock_threshold')
                                    ->label('Low Stock Threshold')
                                    ->numeric()
                                    ->default(5)
                                    ->minValue(0)
                                    ->helperText('Alert when stock falls below this number.'),

                                Forms\Components\TextInput::make('barcode')
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('weight')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01),

                                Forms\Components\Select::make('weight_unit')
                                    ->options([
                                        'kg' => 'Kilograms (kg)',
                                        'g' => 'Grams (g)',
                                        'lb' => 'Pounds (lb)',
                                        'oz' => 'Ounces (oz)',
                                    ])
                                    ->default('kg'),

                                Forms\Components\Fieldset::make('Dimensions')
                                    ->schema([
                                        Forms\Components\TextInput::make('length')
                                            ->numeric()
                                            ->minValue(0)
                                            ->step(0.01)
                                            ->suffix('cm'),

                                        Forms\Components\TextInput::make('width')
                                            ->numeric()
                                            ->minValue(0)
                                            ->step(0.01)
                                            ->suffix('cm'),

                                        Forms\Components\TextInput::make('height')
                                            ->numeric()
                                            ->minValue(0)
                                            ->step(0.01)
                                            ->suffix('cm'),
                                    ])
                                    ->columns(3),
                            ])
                            ->columns(2),

                        Forms\Components\Tabs\Tab::make('Options & Variants')
                            ->schema([
                                Forms\Components\Repeater::make('options')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Option Name')
                                            ->placeholder('e.g., Color, Size, Material')
                                            ->required()
                                            ->maxLength(50),

                                        Forms\Components\TagsInput::make('values')
                                            ->label('Option Values')
                                            ->placeholder('Type a value and press Enter')
                                            ->required(),
                                    ])
                                    ->columns(2)
                                    ->maxItems(3)
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Option')
                                    ->collapsible()
                                    ->columnSpanFull()
                                    ->helperText('Define up to 3 options (e.g., Color, Size). After saving, use the Variants tab below to generate combinations.'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Status & Visibility')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                    ])
                                    ->required()
                                    ->default('draft'),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured Product')
                                    ->default(false),

                                Forms\Components\Toggle::make('is_digital')
                                    ->label('Digital Product')
                                    ->default(false)
                                    ->live(),

                                Forms\Components\TextInput::make('digital_file_path')
                                    ->label('Digital File Path')
                                    ->maxLength(500)
                                    ->visible(fn (Forms\Get $get): bool => $get('is_digital')),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Publish Date'),
                            ])
                            ->columns(2),

                        Forms\Components\Tabs\Tab::make('Images')
                            ->schema([
                                Forms\Components\FileUpload::make('product_images')
                                    ->label('Product Images')
                                    ->image()
                                    ->multiple()
                                    ->reorderable()
                                    ->directory('products')
                                    ->disk('public')
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('1:1')
                                    ->imageResizeTargetWidth('800')
                                    ->imageResizeTargetHeight('800')
                                    ->maxSize(2048)
                                    ->maxFiles(10)
                                    ->helperText('Upload up to 10 images. First image will be set as primary. Max 2MB each.')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->maxLength(70)
                                    ->helperText('Recommended: 50-60 characters.'),

                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->maxLength(160)
                                    ->rows(3)
                                    ->helperText('Recommended: 150-160 characters.'),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images.path')
                    ->label('Image')
                    ->disk('public')
                    ->circular()
                    ->stacked()
                    ->limit(1)
                    ->defaultImageUrl(url('/images/placeholder.png')),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('brand.name')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('price')
                    ->money(currency_code())
                    ->sortable(),

                Tables\Columns\TextColumn::make('compare_at_price')
                    ->money(currency_code())
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->sortable()
                    ->color(fn (Product $record): string => match (true) {
                        $record->stock_quantity <= 0 => 'danger',
                        $record->isLowStock() => 'warning',
                        default => 'success',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'active' => 'success',
                        'inactive' => 'danger',
                    }),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),

                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('brand_id')
                    ->relationship('brand', 'name')
                    ->label('Brand')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),

                Tables\Filters\TernaryFilter::make('is_digital')
                    ->label('Digital Product'),

                Tables\Filters\Filter::make('low_stock')
                    ->query(fn (Builder $query): Builder => $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold')->where('stock_quantity', '>', 0))
                    ->label('Low Stock'),

                Tables\Filters\Filter::make('out_of_stock')
                    ->query(fn (Builder $query): Builder => $query->where('stock_quantity', '<=', 0))
                    ->label('Out of Stock'),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Set Active')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['status' => 'active']))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Set Inactive')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['status' => 'inactive']))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('toggle_featured')
                        ->label('Toggle Featured')
                        ->icon('heroicon-o-star')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_featured' => !$record->is_featured])))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\VariantsRelationManager::class,
            RelationManagers\ImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'sku', 'short_description'];
    }
}
