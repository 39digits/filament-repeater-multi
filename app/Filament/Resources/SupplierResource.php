<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use App\Models\Variant;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Repeater::make('supplierProductVariants')
                    ->relationship('supplierProductVariants')
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->reactive()
                            // This is a workaround to reset the variant_id field when the product_id changes.
                            ->afterStateUpdated(fn (callable $set) => $set('variant_id', []))
                            ->required(),
                        Select::make('variant_id')
                            // Is setting the relationship required if we're using the options method?
                            //->relationship('variant', 'name')
                            // The options are dependent on the product_id.
                            ->options(function (callable $get) {
                                $product_id = $get('product_id');
                                return Variant::whereHas('products', function ($query) use ($product_id) {
                                    $query->where('products.id', $product_id);
                                })->pluck('name', 'id');
                            })

                            // Turning it into a multi-select turns this field into an array.
                            // And I don't know how to handle the data to create a single
                            // row in the pivot table.  As the data doesn't persist in the
                            // $data or $record variables when trying to handleRecordUpdate / Create.
                            //->multiple()
                            //->preload()

                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
            ])
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
