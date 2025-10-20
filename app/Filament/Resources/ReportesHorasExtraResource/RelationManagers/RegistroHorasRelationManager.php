<?php

namespace App\Filament\Resources\ReportesHorasExtraResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Carbon\Carbon;

class RegistroHorasRelationManager extends RelationManager
{
    protected static string $relationship = 'registros';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // 🔹 Fecha trabajada
                Forms\Components\DatePicker::make('fecha_trabajada')
                    ->label('Fecha trabajada')
                    ->required(),

                // 🔹 Actividad específica (Observación)
                Forms\Components\TextInput::make('actividad_espec')
                    ->label('Actividad específica'),

                // 🔹 Puesto ocupado (FK directa en registro_horas)
                // Usa la relación belongsTo('puestoOcupado') del modelo RegistroHora
                Forms\Components\Select::make('puesto_ocupado')
                    ->label('Puesto ocupado')
                    ->relationship('puestoOcupado', 'nombre')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->columnSpanFull(),

                // 🔹 Hora de inicio
                Forms\Components\TimePicker::make('hora_inicio')
                    ->required()
                    ->label('Hora de inicio')
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $this->calcularTotalHoras($set, $get);
                    }),

                // 🔹 Hora de finalización
                Forms\Components\TimePicker::make('hora_fin')
                    ->required()
                    ->label('Hora de finalización')
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $this->calcularTotalHoras($set, $get);
                    }),

                // 🔹 Total de horas (calculado)
                Forms\Components\TextInput::make('total_horas')
                    ->label('Total de horas')
                    ->disabled()         // visible pero no editable
                    ->dehydrated(true)   // se guarda el valor
                    ->numeric()
                    ->suffix('h'),

                // 🔹 Tipo de hora extra
                Forms\Components\Select::make('tipo_hora_extra')
                    ->label('Tipo de Hora Extra')
                    ->options([
                        'normal'   => 'Normal',
                        'nocturna' => 'Nocturna',
                        'feriado'  => 'Feriado',
                    ])
                    ->nullable(),
            ]);
    }

    /**
     * Calcula la diferencia entre hora_inicio y hora_fin en horas decimales
     */
    private function calcularTotalHoras(Set $set, Get $get): void
    {
        $inicio = $get('hora_inicio');
        $fin = $get('hora_fin');

        if ($inicio && $fin) {
            $inicioCarbon = Carbon::parse($inicio);
            $finCarbon = Carbon::parse($fin);

            // Si fin < inicio, pasó medianoche
            if ($finCarbon->lessThan($inicioCarbon)) {
                $finCarbon->addDay();
            }

            $diferencia = $inicioCarbon->diffInMinutes($finCarbon) / 60;
            $set('total_horas', round($diferencia, 2));
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reporte.operario.nombre_completo')
                    ->label('Operario'),

                Tables\Columns\TextColumn::make('fecha_trabajada')
                    ->label('Fecha')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('hora_inicio')
                    ->label('De'),

                Tables\Columns\TextColumn::make('hora_fin')
                    ->label('A'),

                Tables\Columns\TextColumn::make('total_horas')
                    ->label('Hs'),

                Tables\Columns\TextColumn::make('tipo_hora_extra')
                    ->label('Tipo'),

                Tables\Columns\TextColumn::make('actividad_espec')
                    ->label('Observación')
                    ->wrap(),

                // ✅ Columna correcta: puesto ocupado directo del registro
                Tables\Columns\TextColumn::make('puestoOcupado.nombre')
                    ->label('Puesto ocupado'),
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
