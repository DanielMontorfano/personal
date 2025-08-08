<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InduccionResource\Pages;
use App\Filament\Resources\InduccionResource\RelationManagers;
use App\Models\Induccion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InduccionResource extends Resource
{
    protected static ?string $model = Induccion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListInduccions::route('/'),
            'create' => Pages\CreateInduccion::route('/create'),
            'edit' => Pages\EditInduccion::route('/{record}/edit'),
        ];
    }
}
