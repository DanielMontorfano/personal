<?php

namespace App\Filament\Widgets;

use App\Models\Ingreso;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;

class OperariosPresentesTable extends BaseWidget
{
    protected static ?string $heading = 'Operarios en Planta Hoy';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Ingreso::query()
                    ->whereDate('fecha_ingreso', '<=', today())
                    ->where(function ($query) {
                        $query->whereNull('fecha_baja')
                              ->orWhereDate('fecha_baja', '>=', today());
                    })
                    ->with(['operario', 'solicitante'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('operario.nombre_completo')
                    ->label('Operario')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('solicitante.sector')
                    ->label('Sector')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('fecha_ingreso')
                    ->label('Ingreso')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('solicitante.sector');
    }
}
