<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ZafraResource\Pages;
use App\Filament\Resources\ZafraResource\RelationManagers;
use App\Models\Zafra;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ZafraResource extends Resource
{
    protected static ?string $model = Zafra::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Definir duración';
    protected static ?string $modelLabel = 'Zafra';
    protected static ?string $pluralModelLabel = 'Definiciones de zafras'; 
    protected static ?string $navigationGroup = 'Zafra'; // ✅ Más simple que el método
    protected static ?int $navigationSort = 3; // ✅ Más simple que el método

    // 👇 Agregar estos métodos para la navegación
    public static function getNavigationGroup(): ?string
    {
        return 'Zafra';
    }

    public static function getNavigationSort(): ?int
    {
        return 3; // Tercer elemento en el grupo Zafra
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej: Zafra 2024/2025'),
                    
                Forms\Components\DatePicker::make('fecha_inicio')
                    ->label('Fecha de Inicio')
                    ->native(false)
                    ->displayFormat('d/m/Y'),
                    
                Forms\Components\DatePicker::make('fecha_fin')
                    ->label('Fecha de Finalización')
                    ->native(false)
                    ->displayFormat('d/m/Y'),
                    
                Forms\Components\Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->columnSpanFull()
                    ->maxLength(65535)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->date('d/m/Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('fecha_fin')
                    ->date('d/m/Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('activas')
                    ->label('Zafras activas')
                    ->query(fn (Builder $query): Builder => $query->where('fecha_fin', '>=', now()))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('fecha_inicio', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\PlanillasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListZafras::route('/'),
            'create' => Pages\CreateZafra::route('/create'),
            'edit' => Pages\EditZafra::route('/{record}/edit'),
        ];
    }
}