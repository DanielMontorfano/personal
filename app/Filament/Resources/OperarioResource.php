<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OperarioResource\Pages;
use App\Filament\Resources\OperarioResource\RelationManagers;
use App\Models\Operario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\{TextInput, DatePicker, Select};

class OperarioResource extends Resource
{
    protected static ?string $model = Operario::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    
    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('legajo')
                ->numeric()
                ->required()
                ->unique(ignoreRecord: true),
    
            TextInput::make('nombre_completo')
                ->required()
                ->maxLength(255),
    
            Select::make('tipo_liquidacion')
                ->options([
                    'mensual' => 'Mensual',
                    'jornalizado' => 'Jornalizado',
                ])
                ->required(),
    
            DatePicker::make('fecha_ingreso'),
    
            TextInput::make('direccion'),
    
            TextInput::make('dni'),
    
            DatePicker::make('fecha_nacimiento'),
    
            TextInput::make('cuil'),
    
            TextInput::make('categoria'),
    
            TextInput::make('sector'),
    
            TextInput::make('tarea'),
    
            TextInput::make('gerencia'),
        ]);
    }
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('legajo')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('nombre_completo')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('tipo_liquidacion')
                    ->label('Tipo')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('fecha_ingreso')
                    ->date('d/m/Y')
                    ->sortable(),
    
                Tables\Columns\TextColumn::make('direccion')
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('dni')
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('fecha_nacimiento')
                    ->date('d/m/Y')
                    ->sortable(),
    
                Tables\Columns\TextColumn::make('cuil')
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('categoria')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('sector')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('tarea')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('gerencia')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOperarios::route('/'),
            'create' => Pages\CreateOperario::route('/create'),
            'edit' => Pages\EditOperario::route('/{record}/edit'),
        ];
    }
}
