<?php

namespace App\Filament\Resources;

use App\Models\AsignacionZafra;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Form;
// Agrega estos use al inicio del archivo
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Pages\ListAsignacionZafras;
class AsignacionZafraResource extends Resource
{
    protected static ?string $model = AsignacionZafra::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Asignaciones de puestos';
    protected static ?string $modelLabel = 'Asignación del puesto';
    protected static ?string $pluralModelLabel = 'Asignaciones de puestos'; 
    protected static ?string $navigationGroup = 'Zafra'; // ✅ Más simple que el método
    protected static ?int $navigationSort = 3; // ✅ Más simple que el método

    /*// Ocultar creación/edición directa (solo se crean desde PlanillaZafra)
    public static function canCreate(): bool
    {
        return false;
    }*/

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Formulario vacío (solo lectura)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('planillaZafra.numero')
                    ->label('Planilla')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('operario.nombre_completo')
                    ->label('Operario')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('puesto.nombre')
                    ->label('Puesto')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('turno')
                    ->label('Turno')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Mañana' => 'info',
                        'Tarde' => 'warning',
                        'Noche' => 'dark',
                    }),
                
                Tables\Columns\TextColumn::make('categoria_asignada')
                    ->label('Categoría')
                    ->alignCenter(),
                
                Tables\Columns\IconColumn::make('ingresado')
                    ->label('Estado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('turno')
                    ->options([
                        'Mañana' => 'Mañana',
                        'Tarde' => 'Tarde',
                        'Noche' => 'Noche',
                    ]),
                
                Tables\Filters\Filter::make('ingresado')
                    ->label('Solo pendientes')
                    ->query(fn (Builder $query) => $query->where('ingresado', false)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), // Solo lectura
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\AsignacionZafraResource\Pages\ListAsignacionZafras::route('/'),
           // 'view' => Pages\ViewAsignacionZafra::route('/{record}'),
        ];
    }
}