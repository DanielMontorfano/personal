<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SolicitanteResource\Pages;
use App\Filament\Resources\SolicitanteResource\RelationManagers;
use App\Models\Solicitante;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{TextInput, Select};
use Filament\Tables\Columns\TextColumn;

class SolicitanteResource extends Resource
{
    protected static ?string $model = Solicitante::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nombre_completo')
                ->required()
                ->maxLength(255),
    
            TextInput::make('cargo')
                ->label('Cargo (jefe, encargado...)')
                ->maxLength(100),
    
            TextInput::make('sector')
                ->required()
                ->maxLength(100),
        ]);
    }

            public static function table(Table $table): Table
        {
            return $table
                ->columns([
                    TextColumn::make('nombre_completo')->searchable()->sortable(),
                    TextColumn::make('cargo')->sortable(),
                    TextColumn::make('sector')->sortable(),
                ])
                ->actions([
                    Tables\Actions\EditAction::make(),
                ])
                ->bulkActions([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSolicitantes::route('/'),
            'create' => Pages\CreateSolicitante::route('/create'),
            'edit' => Pages\EditSolicitante::route('/{record}/edit'),
        ];
    }
}
