<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AutorizacionResource\Pages;
use App\Models\Autorizacion;
use App\Models\Ingreso;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\{TextInput, Textarea, Select, DatePicker};
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;

class AutorizacionResource extends Resource
{
    protected static ?string $model = Autorizacion::class;

    protected static ?string $navigationGroup = 'Recursos';
    protected static ?string $navigationLabel = 'Autorizaciones';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    // Definición del formulario
    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('ingreso_id')
                ->label('Ingreso')
                ->options(Ingreso::all()->pluck('id', 'id')->toArray()) // Obtener los ingresos
                ->required(),
                
            TextInput::make('jefe_sector')
                ->nullable()
                ->maxLength(255),
                
            TextInput::make('medicina_laboral')
                ->nullable()
                ->maxLength(255),
                
            TextInput::make('seguridad_higiene')
                ->nullable()
                ->maxLength(255),
                
            TextInput::make('personal')
                ->nullable()
                ->maxLength(255),
                
            TextInput::make('jefe_planta')
                ->nullable()
                ->maxLength(255),
                
            DatePicker::make('fecha_autorizacion')
                ->nullable(),
                
            Textarea::make('detalle_aprobacion')
                ->nullable(),
        ]);
    }

    // Definición de la tabla
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->searchable(),
                TextColumn::make('ingreso_id')->sortable()->searchable(),
                TextColumn::make('fecha_autorizacion')->sortable(),
                TextColumn::make('detalle_aprobacion')->limit(50),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    // Definición de las páginas
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAutorizacions::route('/'),
            'create' => Pages\CreateAutorizacion::route('/create'),
            'edit' => Pages\EditAutorizacion::route('/{record}/edit'),
        ];
    }
}
