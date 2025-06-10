<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getNavigationLabel(): string
    {
        return __('custom.user.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('custom.user.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('custom.user.plural_label');
    }

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->label(__('custom.user.fields.name'))
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->label(__('custom.user.fields.email'))
                ->email()
                ->required()
                ->maxLength(255)
                ->unique(User::class, 'email', ignoreRecord: true),
            Forms\Components\FileUpload::make('photo')
                ->label(__('custom.user.fields.photo'))
                ->image()
                ->directory('users/photos')
                ->disk('public')
                ->imagePreviewHeight('100')
                ->deleteUploadedFileUsing(function ($file, $record) {
                    if ($record && $record->photo) {
                        Storage::disk('public')->delete($record->photo);
                    }
                }),
            Forms\Components\TextInput::make('password')
                ->label(__('custom.user.fields.password'))
                ->password()
                ->required(fn($livewire) => $livewire instanceof Pages\CreateUser)
                ->minLength(8)
                ->dehydrateStateUsing(fn($state) => $state ? bcrypt($state) : null)
                ->visible(fn($livewire) => $livewire instanceof Pages\CreateUser || $livewire instanceof Pages\EditUser)
                ->dehydrated(fn($state) => filled($state)),
            Forms\Components\Select::make('roles')
                ->label(__('custom.user.fields.roles'))
                ->multiple()
                ->relationship('roles', 'name')
                ->preload()
                ->searchable()
                // @intelephense-ignore-next-line
                ->visible(fn() => auth()->user()?->hasRole('superadmin'))
        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\ImageColumn::make('photo')
                ->label(__('custom.user.fields.photo'))
                ->circular()
                ->default(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name))
                ->height(40)
                ->width(40),
            Tables\Columns\TextColumn::make('name')
                ->label(__('custom.user.fields.name'))
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('email')
                ->label(__('custom.user.fields.email'))
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label(__('custom.user.fields.created_at'))
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
            Tables\Columns\TextColumn::make('updated_at')
                ->label(__('custom.user.fields.updated_at'))
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
            Tables\Columns\TextColumn::make('deleted_at')
                ->label(__('custom.user.fields.deleted_at'))
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
            Tables\Columns\TextColumn::make('roles.name')
                ->label(__('custom.user.fields.roles'))
                ->badge()
                ->searchable()
                // @intelephense-ignore-next-line
                ->visible(fn() => auth()->user()?->hasRole('superadmin')),
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
            ]);
        // ...existing code...
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
