<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanillaZafraResource\Pages;
use App\Models\PlanillaZafra;
use App\Models\Zafra;
use App\Models\Solicitante;
use App\Models\Operario;
use App\Models\Puesto;
use App\Models\AsignacionZafra;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\PseudoTypes\True_;

class PlanillaZafraResource extends Resource
{
    protected static ?string $model = PlanillaZafra::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Planillas de Zafra';
    protected static ?string $modelLabel = 'Planilla de Zafra';
    protected static ?string $pluralModelLabel = 'Planillas de Zafra'; 
    protected static ?string $navigationGroup = 'Zafra'; // ✅ Más simple que el método
    protected static ?int $navigationSort = 1; // ✅ Más simple que el método

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Datos de la Planilla')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('zafra_id')
                                    ->label('Zafra')
                                    ->relationship('zafra', 'nombre')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                
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
                                
                                DatePicker::make('fecha')
                                    ->label('Fecha')
                                    ->required()
                                    ->default(now()),
                                
                                Select::make('solicitante_id')
                                    ->label('Solicitante')
                                    ->relationship('solicitante', 'nombre_completo')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                
                              //  TextInput::make('numero')
                               //     ->label('Número de Planilla')
                                 //   ->maxLength(191),
                            ]),
                        
                        Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Asignaciones')
                    ->schema([
                        Repeater::make('asignaciones_temporales')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Select::make('puesto_id')
                                            ->label('Función/Puesto')
                                            ->options(function (Get $get) {
                                                $todosPuestos = Puesto::orderBy('orden')
                                                    ->orderBy('nombre')
                                                    ->pluck('nombre', 'id')
                                                    ->toArray();
                                                
                                                $asignacionesTemporales = $get('../../asignaciones_temporales') ?? [];
                                                $puestosSeleccionados = [];
                                                
                                                foreach ($asignacionesTemporales as $index => $item) {
                                                    if (!empty($item['puesto_id'])) {
                                                        $puestosSeleccionados[] = $item['puesto_id'];
                                                    }
                                                }
                                                
                                                $currentIndex = array_search($get('puesto_id'), $puestosSeleccionados);
                                                if ($currentIndex !== false) {
                                                    unset($puestosSeleccionados[$currentIndex]);
                                                }
                                                
                                                $puestosDisponibles = array_diff_key($todosPuestos, array_flip($puestosSeleccionados));
                                                
                                                $puestoActual = $get('puesto_id');
                                                if ($puestoActual && !isset($puestosDisponibles[$puestoActual])) {
                                                    $puesto = Puesto::find($puestoActual);
                                                    if ($puesto) {
                                                        $puestosDisponibles[$puestoActual] = $puesto->nombre;
                                                    }
                                                }
                                                
                                                return $puestosDisponibles;
                                            })
                                            ->required()
                                            ->searchable()
                                            ->live()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                if ($state) {
                                                    $puesto = Puesto::find($state);
                                                    if ($puesto) {
                                                        $set('categoria_puesto_manana', $puesto->categoria);
                                                        $set('categoria_puesto_tarde', $puesto->categoria);
                                                        $set('categoria_puesto_noche', $puesto->categoria);
                                                        $set('categoria_puesto_pordia', $puesto->categoria);
                                                    }
                                                }
                                            })
                                            ->columnSpan(1),

                                        Toggle::make('es_por_dia')
                                            ->label('¿Asignación por día completo?')
                                            ->helperText('Si está activado, se asigna un solo operario para todo el día. Si no, se asigna una terna (Mañana/Tarde/Noche)')
                                            ->live()
                                            ->afterStateUpdated(function ($state, Set $set) {
                                                if ($state) {
                                                    $set('operario_manana', null);
                                                    $set('operario_tarde', null);
                                                    $set('operario_noche', null);
                                                    $set('categoria_operario_manana', null);
                                                    $set('categoria_operario_tarde', null);
                                                    $set('categoria_operario_noche', null);
                                                } else {
                                                    $set('operario_pordia', null);
                                                    $set('categoria_operario_pordia', null);
                                                }
                                            })
                                            ->columnSpan(1),
                                    ]),
                                
                                Section::make('☀️ ASIGNACIÓN POR DÍA COMPLETO')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('operario_pordia')
                                                    ->label('Operario')
                                                    ->options(function () {
                                                        return Operario::query()
                                                            ->orderBy('nombre_completo')
                                                            ->get()
                                                            ->mapWithKeys(function ($operario) {
                                                                return [
                                                                    $operario->id => "#{$operario->legajo} - {$operario->nombre_completo}"
                                                                ];
                                                            });
                                                    })
                                                    ->searchable()
                                                    ->preload()
                                                    ->live()
                                                    ->afterStateUpdated(function (Set $set, $state) {
                                                        if ($state) {
                                                            $operario = Operario::find($state);
                                                            if ($operario) {
                                                                $set('categoria_operario_pordia', $operario->categoria);
                                                            }
                                                        }
                                                    })
                                                    ->columnSpan(1),
                                                
                                                Grid::make(4)
                                                    ->schema([
                                                        TextInput::make('categoria_puesto_pordia')
                                                            ->label('CCT')
                                                            ->disabled()
                                                            ->dehydrated()
                                                            ->default(null),
                                                        
                                                        TextInput::make('categoria_operario_pordia')
                                                            ->label('Cat. Op.')
                                                            ->disabled()
                                                            ->dehydrated()
                                                            ->default(null),
                                                        
                                                        TextInput::make('categoria_mayor_pordia')
                                                            ->label('M.F.')
                                                            ->maxLength(1)
                                                            ->rule('regex:/^[1-8]$/')
                                                            ->validationMessages([
                                                                'regex' => 'Solo se permite un número entre 1 y 8.',
                                                            ])
                                                            ->default(null),
                                                        
                                                        Select::make('condicion_pordia')
                                                            ->label('Cond.')
                                                            ->options([
                                                                'P' => 'P',
                                                                'T' => 'T', 
                                                                'E' => 'E'
                                                            ])
                                                            ->default('T')
                                                            ->required()
                                                            ->native(false),
                                                    ])
                                                    ->columnSpan(1),
                                            ])
                                    ])
                                    ->visible(fn (Get $get): bool => $get('es_por_dia') === true)
                                    ->compact(),

                                Section::make('🔄 TERNA DE TURNOS')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Section::make('🌅 MAÑANA')
                                                    ->schema([
                                                        Select::make('operario_manana')
                                                            ->label('Operario')
                                                            ->options(function () {
                                                                return Operario::query()
                                                                    ->orderBy('nombre_completo')
                                                                    ->get()
                                                                    ->mapWithKeys(function ($operario) {
                                                                        return [
                                                                            $operario->id => "#{$operario->legajo} - {$operario->nombre_completo}"
                                                                        ];
                                                                    });
                                                            })
                                                            ->searchable()
                                                            ->preload()
                                                            ->live()
                                                            ->afterStateUpdated(function (Set $set, $state) {
                                                                if ($state) {
                                                                    $operario = Operario::find($state);
                                                                    if ($operario) {
                                                                        $set('categoria_operario_manana', $operario->categoria);
                                                                    }
                                                                }
                                                            }),
                                                        
                                                        Grid::make(4)
                                                            ->schema([
                                                                TextInput::make('categoria_puesto_manana')
                                                                    ->label('CCT')
                                                                    ->disabled()
                                                                    ->dehydrated()
                                                                    ->default(null),
                                                                
                                                                TextInput::make('categoria_operario_manana')
                                                                    ->label('Cat. Op.')
                                                                    ->disabled()
                                                                    ->dehydrated()
                                                                    ->default(null),
                                                                
                                                                TextInput::make('categoria_mayor_manana')
                                                                    ->label('M.F.')
                                                                    ->maxLength(1)
                                                                    ->rule('regex:/^[1-8]$/')
                                                                    ->validationMessages([
                                                                        'regex' => 'Solo se permite un número entre 1 y 8.',
                                                                    ])
                                                                    ->default(null),
                                                                
                                                                Select::make('condicion_manana')
                                                                    ->label('Cond.')
                                                                    ->options([
                                                                        'P' => 'P',
                                                                        'T' => 'T', 
                                                                        'E' => 'E'
                                                                    ])
                                                                    ->default('T')
                                                                    ->required()
                                                                    ->native(false),
                                                            ])
                                                    ])
                                                    ->columnSpan(1)
                                                    ->compact(),

                                                Section::make('🌤️ TARDE')
                                                    ->schema([
                                                        Select::make('operario_tarde')
                                                            ->label('Operario')
                                                            ->options(function () {
                                                                return Operario::query()
                                                                    ->orderBy('nombre_completo')
                                                                    ->get()
                                                                    ->mapWithKeys(function ($operario) {
                                                                        return [
                                                                            $operario->id => "#{$operario->legajo} - {$operario->nombre_completo}"
                                                                        ];
                                                                    });
                                                            })
                                                            ->searchable()
                                                            ->preload()
                                                            ->live()
                                                            ->afterStateUpdated(function (Set $set, $state) {
                                                                if ($state) {
                                                                    $operario = Operario::find($state);
                                                                    if ($operario) {
                                                                        $set('categoria_operario_tarde', $operario->categoria);
                                                                    }
                                                                }
                                                            }),
                                                        
                                                        Grid::make(4)
                                                            ->schema([
                                                                TextInput::make('categoria_puesto_tarde')
                                                                    ->label('CCT')
                                                                    ->disabled()
                                                                    ->dehydrated()
                                                                    ->default(null),
                                                                
                                                                TextInput::make('categoria_operario_tarde')
                                                                    ->label('Cat. Op.')
                                                                    ->disabled()
                                                                    ->dehydrated()
                                                                    ->default(null),
                                                                
                                                                TextInput::make('categoria_mayor_tarde')
                                                                    ->label('M.F.')
                                                                    ->maxLength(1)
                                                                    ->rule('regex:/^[1-8]$/')
                                                                    ->validationMessages([
                                                                        'regex' => 'Solo se permite un número entre 1 y 8.',
                                                                    ])
                                                                    ->default(null),
                                                                
                                                                Select::make('condicion_tarde')
                                                                    ->label('Cond.')
                                                                    ->options([
                                                                        'P' => 'P',
                                                                        'T' => 'T', 
                                                                        'E' => 'E'
                                                                    ])
                                                                    ->default('T')
                                                                    ->required()
                                                                    ->native(false),
                                                            ])
                                                    ])
                                                    ->columnSpan(1)
                                                    ->compact(),

                                                Section::make('🌙 NOCHE')
                                                    ->schema([
                                                        Select::make('operario_noche')
                                                            ->label('Operario')
                                                            ->options(function () {
                                                                return Operario::query()
                                                                    ->orderBy('nombre_completo')
                                                                    ->get()
                                                                    ->mapWithKeys(function ($operario) {
                                                                        return [
                                                                            $operario->id => "#{$operario->legajo} - {$operario->nombre_completo}"
                                                                        ];
                                                                    });
                                                            })
                                                            ->searchable()
                                                            ->preload()
                                                            ->live()
                                                            ->afterStateUpdated(function (Set $set, $state) {
                                                                if ($state) {
                                                                    $operario = Operario::find($state);
                                                                    if ($operario) {
                                                                        $set('categoria_operario_noche', $operario->categoria);
                                                                    }
                                                                }
                                                            }),
                                                        
                                                        Grid::make(4)
                                                            ->schema([
                                                                TextInput::make('categoria_puesto_noche')
                                                                    ->label('CCT')
                                                                    ->disabled()
                                                                    ->dehydrated()
                                                                    ->default(null),
                                                                
                                                                TextInput::make('categoria_operario_noche')
                                                                    ->label('Cat. Op.')
                                                                    ->disabled()
                                                                    ->dehydrated()
                                                                    ->default(null),
                                                                
                                                                TextInput::make('categoria_mayor_noche')
                                                                    ->label('M.F.')
                                                                    ->maxLength(1)
                                                                    ->rule('regex:/^[1-8]$/')
                                                                    ->validationMessages([
                                                                        'regex' => 'Solo se permite un número entre 1 y 8.',
                                                                    ])
                                                                    ->default(null),
                                                                
                                                                Select::make('condicion_noche')
                                                                    ->label('Cond.')
                                                                    ->options([
                                                                        'P' => 'P',
                                                                        'T' => 'T', 
                                                                        'E' => 'E'
                                                                    ])
                                                                    ->default('T')
                                                                    ->required()
                                                                    ->native(false),
                                                            ])
                                                    ])
                                                    ->columnSpan(1)
                                                    ->compact(),
                                            ]),
                                    ])
                                    ->visible(fn (Get $get): bool => $get('es_por_dia') !== true)
                                    ->compact(),
                            ])
                            ->addActionLabel('Agregar Función')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->itemLabel(function (array $state): ?string {
                                if (!empty($state['puesto_id'])) {
                                    $puesto = Puesto::find($state['puesto_id']);
                                    $tipo = ($state['es_por_dia'] ?? false) ? ' (Por día)' : ' (Terna)';
                                    return $puesto ? $puesto->nombre . $tipo : 'Nueva Función';
                                }
                                return 'Nueva Función';
                            })
                            ->defaultItems(1)
                            ->minItems(1)
                            ->cloneable()
                            ->deletable()
                            ->dehydrated(True)
                            ->rules([
                                function () {
                                    return function (string $attribute, $value, \Closure $fail) {
                                        if (!is_array($value)) {
                                            return;
                                        }
                                        
                                        $puestosUsados = [];
                                        foreach ($value as $index => $item) {
                                            if (!empty($item['puesto_id'])) {
                                                $puestoId = $item['puesto_id'];
                                                if (in_array($puestoId, $puestosUsados)) {
                                                    $puesto = Puesto::find($puestoId);
                                                    $nombrePuesto = $puesto ? $puesto->nombre : "ID {$puestoId}";
                                                    $fail("El puesto '{$nombrePuesto}' está duplicado. Cada puesto solo puede tener una asignación.");
                                                    return;
                                                }
                                                $puestosUsados[] = $puestoId;
                                            }
                                        }
                                    };
                                },
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Número')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('zafra.nombre')
                    ->label('Zafra')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('solicitante.nombre_completo')
                    ->label('Solicitante')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('asignaciones_count')
                    ->label('Asignaciones')
                    ->counts('asignaciones')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('zafra_id')
                    ->label('Zafra')
                    ->relationship('zafra', 'nombre'),
                
                Tables\Filters\SelectFilter::make('solicitante_id')
                    ->label('Solicitante')
                    ->relationship('solicitante', 'nombre_completo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                 Action::make('imprimir')
                ->label('Imprimir')
                ->icon('heroicon-o-printer')
                ->url(fn ($record) => route('planilla-zafra.pdf', $record->id))
                ->openUrlInNewTab(), // Abre el PDF en nueva pestaña



            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nueva Planilla')
                    ->icon('heroicon-o-plus')
                    ->modalHeading('Crear Nueva Planilla de Zafra'),
            ])
            ->defaultSort('fecha', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlanillaZafras::route('/'),
            'create' => Pages\CreatePlanillaZafra::route('/create'),
            'edit' => Pages\EditPlanillaZafra::route('/{record}/edit'),
        ];
    }

    public static function mutateFormDataBeforeFill(array $data): array
    {
        return static::loadAsignaciones($data);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        Log::info('Datos antes de transformar:', $data);
        $transformedData = static::transformFormData($data);
        Log::info('Datos después de transformar:', $transformedData);
        return $transformedData;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        Log::info('Datos antes de transformar (save):', $data);
        $transformedData = static::transformFormData($data);
        Log::info('Datos después de transformar (save):', $transformedData);
        return $transformedData;
    }

    // ESTE ES EL MÉTODO CLAVE QUE TRANSFORMA LOS DATOS
    public static function transformFormData(array $data): array
    {
        $asignaciones = [];
        
        if (isset($data['asignaciones_temporales'])) {
            // Validar puestos duplicados
            $puestosUsados = [];
            foreach ($data['asignaciones_temporales'] as $asignacionTemporal) {
                if (!empty($asignacionTemporal['puesto_id'])) {
                    $puestoId = $asignacionTemporal['puesto_id'];
                    if (in_array($puestoId, $puestosUsados)) {
                        $puesto = Puesto::find($puestoId);
                        $nombrePuesto = $puesto ? $puesto->nombre : "ID {$puestoId}";
                        throw ValidationException::withMessages([
                            'asignaciones_temporales' => "El puesto '{$nombrePuesto}' está duplicado. Cada puesto solo puede tener una asignación."
                        ]);
                    }
                    $puestosUsados[] = $puestoId;
                }
            }
            
            foreach ($data['asignaciones_temporales'] as $asignacionTemporal) {
                $esPorDia = $asignacionTemporal['es_por_dia'] ?? false;
                
                if ($esPorDia) {
                    // Asignación por día completo
                    if (!empty($asignacionTemporal['operario_pordia'])) {
                        $asignaciones[] = [
                            'operario_id' => $asignacionTemporal['operario_pordia'],
                            'puesto_id' => $asignacionTemporal['puesto_id'],
                            'turno' => 'Por día',
                            'categoria_puesto' => $asignacionTemporal['categoria_puesto_pordia'] ?? null,
                            'categoria_operario' => $asignacionTemporal['categoria_operario_pordia'] ?? null,
                            'categoria_mayor' => $asignacionTemporal['categoria_mayor_pordia'] ?? null,
                            'condicion' => $asignacionTemporal['condicion_pordia'] ?? 'T',
                            'ingresado' => false,
                        ];
                    }
                } else {
                    // Terna de turnos
                    $turnos = [
                        'manana' => 'Mañana', 
                        'tarde' => 'Tarde', 
                        'noche' => 'Noche'
                    ];
                    
                    foreach ($turnos as $turnoKey => $turnoNombre) {
                        $operarioKey = "operario_{$turnoKey}";
                        
                        if (!empty($asignacionTemporal[$operarioKey])) {
                            $asignaciones[] = [
                                'operario_id' => $asignacionTemporal[$operarioKey],
                                'puesto_id' => $asignacionTemporal['puesto_id'],
                                'turno' => $turnoNombre,
                                'categoria_puesto' => $asignacionTemporal["categoria_puesto_{$turnoKey}"] ?? null,
                                'categoria_operario' => $asignacionTemporal["categoria_operario_{$turnoKey}"] ?? null,
                                'categoria_mayor' => $asignacionTemporal["categoria_mayor_{$turnoKey}"] ?? null,
                                'condicion' => $asignacionTemporal["condicion_{$turnoKey}"] ?? 'T',
                                'ingresado' => false,
                            ];
                        }
                    }
                }
            }
        }
        
        // Quitar los datos temporales y agregar las asignaciones reales
        unset($data['asignaciones_temporales']);
        $data['asignaciones'] = $asignaciones;
        
        Log::info('Asignaciones finales:', $asignaciones);
        
        return $data;
    }

    public static function loadAsignaciones(array $data): array
    {
        if (isset($data['asignaciones']) && is_array($data['asignaciones'])) {
            $asignacionesTemporales = [];
            $asignacionesByPuesto = collect($data['asignaciones'])->groupBy('puesto_id');
            
            foreach ($asignacionesByPuesto as $puestoId => $asignacionesPuesto) {
                $asignacionTemporal = [
                    'puesto_id' => $puestoId,
                ];
                
                $esPorDia = $asignacionesPuesto->contains('turno', 'Por día');
                $asignacionTemporal['es_por_dia'] = $esPorDia;
                
                foreach ($asignacionesPuesto as $asignacion) {
                    $turnoKey = match($asignacion['turno']) {
                        'Mañana' => 'manana',
                        'Tarde' => 'tarde',
                        'Noche' => 'noche',
                        'Por día' => 'pordia',
                        default => 'manana'
                    };
                    
                    $asignacionTemporal["operario_{$turnoKey}"] = $asignacion['operario_id'];
                    $asignacionTemporal["categoria_puesto_{$turnoKey}"] = $asignacion['categoria_puesto'];
                    $asignacionTemporal["categoria_operario_{$turnoKey}"] = $asignacion['categoria_operario'];
                    $asignacionTemporal["categoria_mayor_{$turnoKey}"] = $asignacion['categoria_mayor'];
                    $asignacionTemporal["condicion_{$turnoKey}"] = $asignacion['condicion'];
                }
                
                $asignacionesTemporales[] = $asignacionTemporal;
            }
            
            $data['asignaciones_temporales'] = $asignacionesTemporales;
        }
        
        return $data;
    }
}