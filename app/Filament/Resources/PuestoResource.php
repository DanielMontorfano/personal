<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PuestoResource\Pages;
use App\Filament\Resources\PuestoResource\Pages\ListPuestos;
use App\Filament\Resources\PuestoResource\Pages\CreatePuesto;
use App\Filament\Resources\PuestoResource\Pages\ViewPuesto;
use App\Filament\Resources\PuestoResource\Pages\EditPuesto;
use App\Models\Puesto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PuestoResource extends Resource
{
    protected static ?string $model = Puesto::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Administración';

    protected static ?string $modelLabel = 'Puesto';

    protected static ?string $pluralModelLabel = 'Puestos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre del Puesto')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej: Operario de Producción, Supervisor, etc.'),
                
                Forms\Components\TextInput::make('categoria')
                    ->label('Categoría CCT')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej: A, B, C, etc.')
                    ->helperText('Categoría según Convenio Colectivo de Trabajo'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre del Puesto')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('categoria')
                    ->label('Categoría CCT')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ingresos_count')
                    ->label('Ingresos')
                    ->counts('ingresos')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categoria')
                    ->label('Categoría CCT')
                    ->options(function () {
                        return Puesto::distinct()->pluck('categoria', 'categoria')->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => ListPuestos::route('/'),
            'create' => CreatePuesto::route('/create'),
           // 'view' => ViewPuesto::route('/{record}'),
            'edit' => EditPuesto::route('/{record}/edit'),
        ];
    }
}