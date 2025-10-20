<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistroHoraResource\Pages;
use App\Models\RegistroHora;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RegistroHoraResource extends Resource
{
    protected static ?string $model = RegistroHora::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Gestión de Horas Extras';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('reporte_id')
                ->label('Reporte')
                ->relationship('reporte', 'numero_reporte')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('operario_id')
                ->label('Operario')
                ->relationship('operario', 'nombre')
                ->searchable()
                ->required(),

            Forms\Components\DatePicker::make('fecha_trabajada')
                ->label('Fechassssssstttt trabajada')
                ->required(),

            Forms\Components\TimePicker::make('hora_inicio')->label('Hora inicio')->required(),
            Forms\Components\TimePicker::make('hora_fin')->label('Hora fin')->required(),

            Forms\Components\TextInput::make('total_horas')
                ->label('Total Horas')
                ->disabled(),

            Forms\Components\Select::make('tipo_hora_extra')
                ->label('Tipo de hora extra')
                ->options([
                    '50' => '50%',
                    '100' => '100%',
                ])
                ->required(),

            Forms\Components\TextInput::make('actividad_espec')->label('Actividad'),

            Forms\Components\TextInput::make('valor_recargo')->label('Recargo')->disabled(),
            Forms\Components\TextInput::make('total_pago')->label('Pago')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('reporte.numero_reporte')->label('Reporte'),
            Tables\Columns\TextColumn::make('operario.nombre')->label('Operario'),
            Tables\Columns\TextColumn::make('fecha_trabajada')->date(),
            Tables\Columns\TextColumn::make('hora_inicio')->time(),
            Tables\Columns\TextColumn::make('hora_fin')->time(),
            Tables\Columns\TextColumn::make('total_horas')->label('Horas'),
            Tables\Columns\TextColumn::make('tipo_hora_extra')->label('Tipo'),
            Tables\Columns\TextColumn::make('total_pago')->money('ARS', true),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistroHoras::route('/'),
            'create' => Pages\CreateRegistroHora::route('/create'),
            'edit' => Pages\EditRegistroHora::route('/{record}/edit'),
        ];
    }
}
