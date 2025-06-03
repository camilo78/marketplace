<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublicationResource\Pages;
use App\Models\Publication;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class PublicationResource extends Resource
{
    protected static ?string $model = Publication::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->unique()
                    ->required(),
                Forms\Components\RichEditor::make('description')
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->numeric(),
                Forms\Components\Select::make('currency')
                    ->options([
                        'HNL' => 'Honduran Lempira',
                        'USD' => 'US Dollar',
                    ])
                    ->default('HNL'),
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Activo',
                        'pending' => 'Pendiente',
                        'sold' => 'Vendido',
                        'expired' => 'Expirado',
                        'inactive' => 'Inactivo',
                    ])
                    ->default('pending'),
                Forms\Components\Toggle::make('is_featured')
                    ->default(false),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable(),
                Tables\Columns\TextColumn::make('price')->sortable(),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->filters([])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPublications::route('/'),
            'create' => Pages\CreatePublication::route('/create'),
            'edit' => Pages\EditPublication::route('/{record}/edit'),
        ];
    }
}