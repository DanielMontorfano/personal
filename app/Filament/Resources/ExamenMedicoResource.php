<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamenMedicoResource\Pages;
use App\Filament\Resources\ExamenMedicoResource\RelationManagers;
use App\Models\ExamenMedico;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExamenMedicoResource extends Resource
{
    protected static ?string $model = ExamenMedico::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Examen médico';
    protected static ?string $modelLabel = 'Examen médico';
    protected static ?string $pluralModelLabel = 'Examenes médicos';
    protected static ?string $navigationGroup = 'Recursos'; // ✅ Más simple que el método
    protected static ?int $navigationSort = 4; // ✅ Más simple que el método

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
            'index' => Pages\ListExamenMedicos::route('/'),
            'create' => Pages\CreateExamenMedico::route('/create'),
            'edit' => Pages\EditExamenMedico::route('/{record}/edit'),
        ];
    }
}
