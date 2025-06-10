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

    public static function getNavigationLabel(): string
    {
        return __('custom.category.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('custom.category.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('custom.category.plural_label');
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label(__('custom.category.fields.name'))
                ->required(),
            Forms\Components\TextInput::make('slug')
                ->label(__('custom.category.fields.slug'))
                ->required()
                ->rules(function (callable $get, ?Category $record) {
                    return $record ? [] : ['unique:categories,slug'];
                }),
            Forms\Components\Textarea::make('description')
                ->label(__('custom.category.fields.description')),
            Forms\Components\Select::make('parent_id')
                ->label(__('custom.category.fields.parent_id'))
                ->searchable()
                ->relationship('parent', 'name')
                ->nullable(),
            Forms\Components\Toggle::make('is_active')
                ->label(__('custom.category.fields.is_active'))
                ->default(true),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('custom.category.fields.name'))
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function (string $state, Category $record) {
                        $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $record->depth);
                        $bullet = $record->depth > 0 ? '✦ &nbsp;&nbsp;' : '';
                        return $indent . $bullet . e($state);
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('custom.category.fields.slug')),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('custom.category.fields.description'))
                    ->wrap(),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label(__('custom.category.fields.is_active')),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('custom.category.actions.edit')),
                Tables\Actions\DeleteAction::make()
                    ->label(__('custom.category.actions.delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label(__('custom.category.actions.delete')),
                ]),
            ])
            ->recordClasses(function (Category $record): ?string {
                return match ($record->depth) {
                    0 => 'bg-white dark:bg-gray-800 font-bold',
                    1 => 'bg-gray-100 dark:bg-gray-700',
                    2 => 'bg-gray-200 dark:bg-gray-600',
                    3 => 'bg-gray-300 dark:bg-gray-500',
                    4 => 'bg-gray-400 dark:bg-gray-400',
                    5 => 'bg-gray-500 dark:bg-gray-300',
                    6 => 'bg-gray-600 dark:bg-gray-200',
                    default => 'bg-gray-100 dark:bg-gray-700',
                };
            })
            ->reorderable(function (Category $record, $newOrder = null): void {
                if ($newOrder === null) {
                    return;
                }
                $siblings = Category::where('parent_id', $record->parent_id)
                    ->orderBy('_lft')
                    ->get();
                $target = $siblings->get($newOrder);
                if ($target && $record->id !== $target->id) {
                    $record->moveAfter($target);
                }
            });
    }

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
