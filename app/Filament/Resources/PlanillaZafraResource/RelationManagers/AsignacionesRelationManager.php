<?php

namespace App\Filament\Resources\PlanillaZafraResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AsignacionesRelationManager extends RelationManager
{
    protected static string $relationship = 'asignaciones';

    protected static ?string $title = 'Asignaciones de Operarios';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('operario_id')
                    ->relationship('operario', 'nombre_completo')
                    ->required(),
                Forms\Components\Select::make('puesto_id')
                    ->relationship('puesto', 'nombre')
                    ->required(),
                Forms\Components\Select::make('turno')
                    ->options([
                        'Mañana' => 'Mañana',
                        'Tarde' => 'Tarde',
                        'Noche' => 'Noche'
                    ])
                    ->required()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('operario.nombre_completo'),
                Tables\Columns\TextColumn::make('puesto.nombre'),
                Tables\Columns\TextColumn::make('turno')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
