<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IngresoResource\Pages;
use App\Models\Ingreso;
use App\Models\Operario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{
    TextInput,
    DatePicker,
    Select,
    Textarea,
    Section,
    Toggle
};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Solicitante;
use App\Models\Sector;
use App\Services\IngresoService;

class IngresoResource extends Resource
{
    protected static ?string $model = Ingreso::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static ?string $navigationLabel = 'Ingresos';
    protected static ?string $modelLabel = 'Ingreso';
    protected static ?string $pluralModelLabel = 'Ingresos';
    protected static ?string $navigationGroup = 'Personal transitorio'; // ✅ Más simple que el método
    protected static ?int $navigationSort = 2; // ✅ Más simple que el método

    // 👇 Agregar estos métodos para la navegación
    public static function getNavigationGroup(): ?string
    {
        return 'Personal transitorio';
    }

    public static function getNavigationSort(): ?int
    {
        return 2; // Segundo elemento en el grupo Personal transitorio
    }

    public static function form(Form $form): Form
    {
        return $form->schema([

            // --- Sección Datos del Operario ---
            Section::make('Datos del Operario')
                ->schema([
                    Select::make('operario_id')
                        ->label('Operario')
                        ->searchable()
                        ->options(function () {
                            return Operario::all()->mapWithKeys(function ($operario) {
                                return [
                                    $operario->id => "{$operario->legajo} - {$operario->nombre_completo}",
                                ];
                            });
                        })
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, $state) {
                            $operario = Operario::find($state);
                            $set('legajo_visible', $operario?->legajo);
                            $set('dni_visible', $operario?->dni);
                            $set('cuil_visible', $operario?->cuil);
                            $set('categoria_visible', $operario?->categoria);
                            $set('sector_visible', $operario?->sector);
                            $set('tarea_visible', $operario?->tarea);
                        })
                        ->required(),

                    TextInput::make('legajo_visible')->label('Legajo')->disabled()->dehydrated(false)->visible(fn ($get) => filled($get('legajo_visible'))),
                    TextInput::make('dni_visible')->label('DNI')->disabled()->dehydrated(false)->visible(fn ($get) => filled($get('dni_visible'))),
                    TextInput::make('cuil_visible')->label('CUIL')->disabled()->dehydrated(false)->visible(fn ($get) => filled($get('cuil_visible'))),
                    TextInput::make('categoria_visible')->label('Categoría')->disabled()->dehydrated(false)->visible(fn ($get) => filled($get('categoria_visible'))),
                    TextInput::make('sector_visible')->label('Sector')->disabled()->dehydrated(false)->visible(fn ($get) => filled($get('sector_visible'))),
                    TextInput::make('tarea_visible')->label('Tarea')->disabled()->dehydrated(false)->visible(fn ($get) => filled($get('tarea_visible'))),
                ])
                ->columns(2)
                ->collapsible(),

            // --- Sección Datos del Ingreso ---
            Section::make('Datos del Ingreso')
            ->schema([
                Select::make('solicitante_id')
                    ->label('Solicitante')
                    ->options(function ($get) {
                        $selectedId = $get('solicitante_id');
                        $query = \App\Models\Solicitante::orderBy('nombre_completo')->limit(10);
                        if ($selectedId && ! $query->pluck('id')->contains($selectedId)) {
                            $query->orWhere('id', $selectedId);
                        }
                        return $query->pluck('nombre_completo', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                
                Select::make('sector_id')
                    ->label('Sector de ingreso')
                    ->options(function ($get) {
                        $selectedId = $get('sector_id');
                        $query = \App\Models\Sector::orderBy('nombre')->limit(10);
                        if ($selectedId && ! $query->pluck('id')->contains($selectedId)) {
                            $query->orWhere('id', $selectedId);
                        }
                        return $query->pluck('nombre', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                    
                DatePicker::make('fecha_ingreso')
                    ->label('Fecha de ingreso')
                    ->required()
                    ->afterStateHydrated(function ($component, $state) {
                        $component->state($state);
                    })
                    ->rule(function (callable $get, ?Ingreso $record) {
                        $fechaIngreso = $get('fecha_ingreso');
                        $fechaBaja = $get('fecha_baja');
                        $operarioId = $get('operario_id');

                        if (!$fechaIngreso || !$operarioId) return null;

                        return function (string $attribute, $value, \Closure $fail) use ($fechaIngreso, $fechaBaja, $operarioId, $record) {
                            $valido = IngresoService::validarSolapamientoOperario(
                                $operarioId,
                                $fechaIngreso,
                                $fechaBaja,
                                $record
                            );

                            if (!$valido) {
                                $fail('Ya existe otro ingreso para este operario en un período superpuesto.');
                            }
                        };
                    }),
        
                DatePicker::make('fecha_baja')
                    ->label('Fecha de baja')
                    ->required()
                    ->rule(function (callable $get) {
                        $fechaIngreso = $get('fecha_ingreso');
                        return $fechaIngreso
                            ? ['after_or_equal:' . $fechaIngreso]
                            : [];
                    }),
                
                Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->rows(3)
                    ->columnSpanFull(),
            ])
            ->columns(2)
            ->collapsible(),
        
            // --- Sección Inducción ---
            Section::make('Inducción de Seguridad e Higiene')
                ->schema([
                    DatePicker::make('induccion.fecha_induccion')->label('Fecha de inducción'),
                    TextInput::make('induccion.responsable')->label('Responsable')->maxLength(100),
                    Textarea::make('induccion.observaciones')->label('Observaciones')->rows(3),
                ])
                ->columns(2)
                ->collapsible(),

            // --- Sección Autorizaciones ---
            Section::make('Autorizaciones')
                ->schema([
                    Toggle::make('autorizacion.aprobado_personal')->label('Aprobado por Departamento de Personal'),
                    Toggle::make('autorizacion.aprobado_medicina')->label('Aprobado por Medicina Laboral'),
                    Toggle::make('autorizacion.aprobado_hys')->label('Aprobado por Seguridad e Higiene'),
                    Toggle::make('autorizacion.aprobado_jefe_planta')->label('Aprobado por 2do Jefe de Planta'),
                    DatePicker::make('autorizacion.fecha_autorizacion')->label('Fecha de autorización'),
                    Textarea::make('autorizacion.observaciones')->rows(3),
                ])
                ->columns(2)
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('operario.nombre_completo')->label('Operario')->sortable()->searchable(),
                TextColumn::make('solicitante.nombre_completo')->label('Solicitante')->sortable()->searchable(),
                TextColumn::make('sector.nombre')->label('Sección')->sortable()->searchable(), // ← Corregido
                TextColumn::make('fecha_ingreso')->date('d/m/Y')->sortable(),
                TextColumn::make('fecha_baja')->date('d/m/Y')->sortable(),
                TextColumn::make('observaciones')->limit(50)->wrap(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIngresos::route('/'),
            'create' => Pages\CreateIngreso::route('/create'),
            'edit' => Pages\EditIngreso::route('/{record}/edit'),
        ];
    }
}