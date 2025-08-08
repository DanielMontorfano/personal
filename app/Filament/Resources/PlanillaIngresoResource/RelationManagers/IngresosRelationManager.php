<?php

namespace App\Filament\Resources\PlanillaIngresoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use App\Models\Operario;
use App\Models\Sector;
use Filament\Forms\Components\Hidden;
use App\Services\IngresoService;
use Filament\Notifications\Notification;

class IngresosRelationManager extends RelationManager
{
    protected static string $relationship = 'ingresos';

    protected static ?string $title = 'Ingresos asociados';

    public function form(Form $form): Form
    {
        return $form->schema([
            Hidden::make('solicitante_id')
                ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->solicitante_id)
                ->required(),

            Forms\Components\Select::make('operario_id')
                ->label('Operario')
                ->relationship('operario', 'nombre_completo')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->legajo} - {$record->nombre_completo}")
                ->searchable(['nombre_completo', 'legajo'])
                ->required()
                ->rule(function (RelationManager $livewire) {
                    return function (string $attribute, $value, \Closure $fail) use ($livewire) {
                        $operariosRepetidos = collect($livewire->ownerRecord->ingresos)
                            ->pluck('operario_id')
                            ->contains($value);

                        if ($operariosRepetidos) {
                            Notification::make()
                                ->danger()
                                ->title('Operario duplicado')
                                ->body('Este operario ya está incluido en la planilla actual.')
                                ->send();
                            
                            $fail('Este operario ya está incluido en la planilla actual.');
                        }
                    };
                }),

            Forms\Components\Select::make('sector_id')
                ->label('Sector de ingreso')
                ->options(function ($get) {
                    $selectedId = $get('sector_id');
                    $query = \App\Models\Sector::orderBy('nombre')->limit(10);
                    if ($selectedId && !$query->pluck('id')->contains($selectedId)) {
                        $query->orWhere('id', $selectedId);
                    }
                    return $query->pluck('nombre', 'id');
                })
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\DatePicker::make('fecha_ingreso')
                ->required()
                ->label('Fecha de ingreso')
                ->rule(function (callable $get, ?\App\Models\Ingreso $record) {
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
                            Notification::make()
                                ->danger()
                                ->title('Período superpuesto')
                                ->body('Ya existe otro ingreso para este operario en las fechas seleccionadas.')
                                ->send();
                                
                            $fail('Ya existe otro ingreso para este operario en un período superpuesto.');
                        }
                    };
                }),

            Forms\Components\DatePicker::make('fecha_baja')
                ->label('Fecha de baja')
                ->required()
                ->nullable()
                ->rule(function (callable $get) {
                    $fechaIngreso = $get('fecha_ingreso');
                    
                    if (!$fechaIngreso) return [];
                    
                    return [
                        'after_or_equal:' . $fechaIngreso,
                        function (string $attribute, $value, \Closure $fail) use ($fechaIngreso) {
                            if ($value && $value < $fechaIngreso) {
                                Notification::make()
                                    ->danger()
                                    ->title('Fecha inválida')
                                    ->body('La fecha de baja no puede ser anterior a la fecha de ingreso.')
                                    ->send();
                                    
                                $fail('La fecha de baja no puede ser anterior a la fecha de ingreso.');
                            }
                        }
                    ];
                }),

            Forms\Components\Textarea::make('observaciones')
                ->label('Observaciones')
                ->rows(2)
                ->nullable(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('operario.nombre_completo')
                    ->label('Operario')
                    ->sortable()
                    ->searchable()
                    ->description(fn ($record) => 'Legajo: ' . $record->operario->legajo),
                    
                Tables\Columns\TextColumn::make('sector.nombre')
                    ->label('Sector')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('fecha_ingreso')
                    ->label('Ingreso')
                    ->date('d/m/Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('fecha_baja')
                    ->label('Baja')
                    ->date('d/m/Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('observaciones')
                    ->label('Observaciones')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->observaciones),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Ingreso creado')
                            ->body('El operario ha sido registrado exitosamente en la planilla')
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Cambios guardados')
                            ->body('Los datos del ingreso se han actualizado correctamente')
                    ),
                    
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record) {
                        // Validación adicional antes de eliminar
                        if ($record->fecha_baja && $record->fecha_baja > now()) {
                            Notification::make()
                                ->danger()
                                ->title('No se puede eliminar')
                                ->body('No puede eliminar un ingreso con fecha de baja futura')
                                ->send();
                            return false;
                        }
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Ingreso eliminado')
                            ->body('El registro ha sido removido de la planilla')
                    ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->before(function ($records) {
                        $tieneFuturos = $records->contains(fn ($record) => $record->fecha_baja && $record->fecha_baja > now());
                        
                        if ($tieneFuturos) {
                            Notification::make()
                                ->danger()
                                ->title('Acción no permitida')
                                ->body('No puede eliminar ingresos con fechas de baja futuras')
                                ->send();
                            return false;
                        }
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Ingresos eliminados')
                            ->body('Los registros seleccionados han sido removidos de la planilla')
                    ),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Agregar ingreso')
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public function getFormValidationErrorNotification(): ?Notification
    {
        return Notification::make()
            ->danger()
            ->title('Error en el formulario')
            ->body('Por favor verifique los datos ingresados. Hay campos requeridos o con información inválida.');
    }
}