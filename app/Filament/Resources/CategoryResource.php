<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('slug')
                ->required()
                ->rules(function (callable $get, ?Category $record) {
                    return $record ? [] : ['unique:categories,slug'];
                }),

            Forms\Components\Textarea::make('description'),
            Forms\Components\Select::make('parent_id')
                ->label('Categoría Padre')
                ->relationship('parent', 'name')
                ->nullable(),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                // Columna "Nombre" con indentación y viñeta para subcategorías
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->sortable()
                    ->formatStateUsing(function (string $state, Category $record) {
                        $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $record->depth);
                        $bullet = $record->depth > 0 ? '✦ &nbsp;&nbsp;' : '';
                        return $indent . $bullet . e($state);
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\BooleanColumn::make('is_active'),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordClasses(function (Category $record): ?string {
                if ($record->depth === -1) {
                    return 'shadow-lg';
                } elseif ($record->depth === 0) {
                    return 'bg-gray-50 shadow-lg';
                } elseif ($record->depth === 1) {
                    return 'bg-gray-100 shadow-lg';
                } elseif ($record->depth === 2) {
                    return 'bg-gray-200 shadow-lg';
                } else {
                    return 'bg-gray-300 shadow-lg';
                }
            })
            // El parámetro $newOrder tiene valor predeterminado para evitar inyección automática.
            ->reorderable(function (Category $record, $newOrder = null): void {
                if ($newOrder === null) {
                    return;
                }
                
                // Obtenemos los hermanos con el mismo parent_id y los ordenamos por _lft.
                $siblings = Category::where('parent_id', $record->parent_id)
                    ->orderBy('_lft')
                    ->get();

                // Seleccionamos el nodo destino en el nuevo orden.
                $target = $siblings->get($newOrder);
                if ($target && $record->id !== $target->id) {
                    $record->moveAfter($target);
                }
            });
    }

    // Se incluye withDepth() para obtener la propiedad "depth" y defaultOrder() para la estructura del árbol.
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withDepth()->defaultOrder();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}