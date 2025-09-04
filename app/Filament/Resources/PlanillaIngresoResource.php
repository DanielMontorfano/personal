<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanillaIngresoResource\Pages;
use App\Filament\Resources\PlanillaIngresoResource\RelationManagers;
use App\Models\PlanillaIngreso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use App\Models\Solicitante;
use Illuminate\Database\Eloquent\Collection; // Añade esta línea

class PlanillaIngresoResource extends Resource
{
    protected static ?string $model = PlanillaIngreso::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // 👇 Agregar estos métodos para la navegación
    public static function getNavigationGroup(): ?string
    {
        return 'Personal transitorio';
    }

    public static function getNavigationSort(): ?int
    {
        return 1; // Primer elemento en el grupo Personal transitorio
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('fecha')
                ->label('Fecha de planilla')
                ->default(now())
                ->required(),
            
            TextInput::make('numero')
                ->label('Número de planilla')
                ->disabled()
                ->dehydrated(false)
                ->visibleOn('edit'),
    
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
            
            Textarea::make('observaciones')
                ->label('Observaciones generales')
                ->rows(3)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fecha')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
    
                TextColumn::make('numero')
                    ->label('Número')
                    ->sortable(),
    
                TextColumn::make('solicitante.nombre_completo')
                    ->label('Solicitante')
                    ->sortable()
                    ->searchable(),
    
                TextColumn::make('observaciones')
                    ->label('Observaciones')
                    ->limit(50),
            ])
            ->filters([
                // Filtros adicionales si los necesitas
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (PlanillaIngreso $record) {
                        // Eliminar registros relacionados primero
                        $record->ingresos()->delete();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Collection $records) {
                            // Eliminar registros relacionados en borrado masivo
                            foreach ($records as $record) {
                                $record->ingresos()->delete();
                            }
                        }),
                ]),
            ])
            ->defaultSort('fecha', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\IngresosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlanillaIngresos::route('/'),
            'create' => Pages\CreatePlanillaIngreso::route('/create'),
            'edit' => Pages\EditPlanillaIngreso::route('/{record}/edit'),
        ];
    }
}