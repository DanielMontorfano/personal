<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SectorResource\Pages;
use App\Filament\Resources\SectorResource\RelationManagers;
use App\Models\Sector;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;







use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class SectorResource extends Resource
{
    protected static ?string $model = Sector::class;

    
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Sectores';
    protected static ?string $modelLabel = 'Sector';
    
    protected static ?string $pluralModelLabel = 'Sectores';
  
    



    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nombre')
                ->label('Nombre del sector')
                ->required()
                ->maxLength(100),
    
            TextInput::make('sigla')
                ->label('Sigla')
                ->maxLength(10),
        ]);
    }
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),
    
                TextColumn::make('sigla')
                    ->label('Sigla')
                    ->sortable(),
            ])
            ->defaultSort('nombre');
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
            'index' => Pages\ListSectors::route('/'),
            'create' => Pages\CreateSector::route('/create'),
            'edit' => Pages\EditSector::route('/{record}/edit'),
        ];
    }
}
