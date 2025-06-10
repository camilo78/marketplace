<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PublicationResource\Pages;
use App\Models\Publication;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Set; // --- IMPORTANTE: Añadir el use para Set
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Support\Str; // --- IMPORTANTE: Para generar el slug automáticamente

class PublicationResource extends Resource
{
    protected static ?string $model = Publication::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getNavigationLabel(): string
    {
        return __('custom.publication.navigation_label');
    }

    public static function getModelLabel(): string
    {
        return __('custom.publication.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('custom.publication.plural_label');
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        // Los administradores pueden ver todo
        if ($user && in_array($user->role, ['admin', 'super-admin'])) {
            return $query;
        }

        // Los usuarios normales solo ven sus publicaciones
        return $query->where('user_id', Auth::id());
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('custom.publication.sections.main_info'))
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label(__('custom.publication.fields.category'))
                                    ->placeholder(__('Selecciona una categoría'))
                                    ->columnSpanFull()
                                    ->options(function () {
                                        $categories = \App\Models\Category::with('children')->whereNull('parent_id')->get();
                                        $options = [];
                                        $addOptions = function ($categories, $prefix = '') use (&$addOptions, &$options) {
                                            foreach ($categories as $category) {
                                                $options[$category->id] = $prefix . $category->name;
                                                if ($category->children && $category->children->count()) {
                                                    $addOptions($category->children, $prefix . '➩ ');
                                                }
                                            }
                                        };
                                        $addOptions($categories);
                                        return $options;
                                    })
                                    ->searchable()
                                    ->required(),

                                Forms\Components\TextInput::make('title')
                                    ->label(__('custom.publication.fields.title'))
                                    ->required()
                                    ->columnSpanFull()
                                    ->maxLength(255)
                                    // --- MEJORA: Genera el slug automáticamente al escribir el título
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                Forms\Components\TextInput::make('slug')
                                    ->label(__('custom.publication.fields.slug'))
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Publication::class, 'slug', ignoreRecord: true),
                                Forms\Components\Toggle::make('is_featured')
                                    ->label(__('custom.publication.fields.is_featured'))
                                    ->inline(false) // Esto coloca el label arriba
                                    ->default(false),

                                Forms\Components\RichEditor::make('description')
                                    ->label(__('custom.publication.fields.description'))
                                    ->required()
                                    ->columnSpanFull(),
                            ])->columns(2),

                        // --- INICIO DE LA MODIFICACIÓN IMPORTANTE ---
                        Forms\Components\Section::make(__('custom.publication.fields.images'))
                            ->schema([
                                Repeater::make('images')
                                    ->label('') // La etiqueta ya está en la sección
                                    ->relationship('images')
                                    ->schema([
                                        FileUpload::make('file_path')
                                            ->label(__('custom.publication.fields.image'))
                                            ->image()
                                            ->directory('publications')
                                            ->imageEditor() // Opcional: permite editar la imagen
                                            ->required(),
                                    ])
                                    ->minItems(1)
                                    ->maxItems(10)
                                    ->orderable('order') // Habilita el drag-and-drop y usa la columna 'order'
                                    ->default([])
                                    ->columnSpanFull()
                                    // --- ESTA ES LA MAGIA ---
                                    // Modifica el estado antes de guardarlo en la base de datos
                                    ->mutateDehydratedStateUsing(function (array $state): array {
                                        return array_map(function (array $image, int $index) {
                                            // Ahora solo nos preocupamos por el orden inicial
                                            $image['order'] = $index + 1;
                                            // La lógica de 'is_cover' se elimina de aquí
                                            return $image;
                                        }, $state, array_keys($state));
                                    }),
                            ]),
                        // --- FIN DE LA MODIFICACIÓN IMPORTANTE ---
                    ])->columnSpan(2),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('custom.publication.sections.details'))
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label(__('custom.publication.fields.price'))
                                    ->numeric()
                                    ->prefixIcon('heroicon-o-currency-dollar'),

                                Forms\Components\Select::make('currency')
                                    ->label(__('custom.publication.fields.currency'))
                                    ->options([
                                        'HNL' => __('custom.publication.currencies.HNL'),
                                        'USD' => __('custom.publication.currencies.USD'),
                                    ])
                                    ->default('HNL'),

                                // --- NUEVO: Campo 'condition' añadido ---
                                Forms\Components\Select::make('condition')
                                    ->label(__('custom.publication.fields.condition'))
                                    ->options([
                                        'new' => __('custom.publication.conditions.new'),
                                        'used' => __('custom.publication.conditions.used'),
                                        'refurbished' => __('custom.publication.conditions.refurbished'),
                                    ]),
                            ])->columns(1),

                        Forms\Components\Section::make(__('custom.publication.sections.status_admin'))
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label(__('custom.publication.fields.status'))
                                    ->options([
                                        'active' => __('custom.publication.statuses.active'),
                                        'pending' => __('custom.publication.statuses.pending'),
                                        'sold' => __('custom.publication.statuses.sold'),
                                        'expired' => __('custom.publication.statuses.expired'),
                                        'inactive' => __('custom.publication.statuses.inactive'),
                                    ])
                                    ->default('pending')
                                    ->required(),

                                // --- NUEVO: Campo 'expires_at' añadido ---
                                Forms\Components\DateTimePicker::make('expires_at')
                                    ->label(__('custom.publication.fields.expires_at')),
                            ]),

                        // --- NUEVO: Sección de ubicación ---
                        Forms\Components\Section::make(__('custom.publication.sections.location'))
                            ->schema([
                                Forms\Components\TextInput::make('location_province')
                                    ->label(__('custom.publication.fields.location_province')),
                                Forms\Components\TextInput::make('location_city')
                                    ->label(__('custom.publication.fields.location_city')),
                                Forms\Components\Textarea::make('location_address')
                                    ->label(__('custom.publication.fields.location_address'))
                                    ->rows(3),
                            ]),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                // --- MEJORA: Muestra la imagen de portada ---
                Tables\Columns\ImageColumn::make('images.file_path')
                    ->label(__('custom.publication.fields.cover_image'))
                    ->getStateUsing(function (Publication $record) {
                        return $record->images()->where('is_cover', true)->value('file_path');
                    }),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('custom.publication.fields.title'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('custom.publication.fields.category'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label(__('custom.publication.fields.price'))
                    ->money(fn($record) => $record->currency)
                    ->sortable(),

                // --- MEJORA: Usa badges para el estado ---
                Tables\Columns\TextColumn::make('status')
                    ->label(__('custom.publication.fields.status'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'active' => 'success',
                        'sold', 'expired' => 'danger',
                        'inactive' => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label(__('custom.publication.fields.is_featured_short'))
                    ->boolean(),

            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
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
