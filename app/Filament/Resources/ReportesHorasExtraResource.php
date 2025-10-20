<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportesHorasExtraResource\Pages;
use App\Filament\Resources\ReportesHorasExtraResource\RelationManagers\RegistroHorasRelationManager;
use App\Models\ReportesHorasExtra;
use App\Models\Sector;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Get;

class ReportesHorasExtraResource extends Resource
{
    protected static ?string $model = ReportesHorasExtra::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Gestión de Horas Extras';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\DatePicker::make('fecha_reporte')
                ->label('Fecha de Reporte')
                ->default(now())
                ->disabled() // 👈 no editable
                ->dehydrated(true) // 👈 se guarda igual en la base
                ->required(),

            Forms\Components\Select::make('periodo_reporte')
                ->label('Período (Mes)')
                ->options([
                    'Enero' => 'Enero',
                    'Febrero' => 'Febrero',
                    'Marzo' => 'Marzo',
                    'Abril' => 'Abril',
                    'Mayo' => 'Mayo',
                    'Junio' => 'Junio',
                    'Julio' => 'Julio',
                    'Agosto' => 'Agosto',
                    'Septiembre' => 'Septiembre',
                    'Octubre' => 'Octubre',
                    'Noviembre' => 'Noviembre',
                    'Diciembre' => 'Diciembre',
                ])
                ->default(fn () => ucfirst(now()->locale('es')->monthName)) // 👈 mes actual en español
                ->required(),

    


            // 🔹 Operario: se elige una sola vez
            Forms\Components\Select::make('operario_id')
                ->label('Operario')
                ->relationship('operario', 'nombre_completo')
                ->searchable()
                ->preload()
                ->disabled(fn (Get $get, $record) => $record !== null)
                ->required(),

            // En forma oculta guarda el usuario autenticado
            Forms\Components\Hidden::make('usuario_id')
                ->default(fn () => Auth::id())
                ->dehydrated(true), // se envía al backend
                

            Forms\Components\Select::make('solicitante_id')
                ->label('Solicitante (Jefe)')
                ->relationship('solicitante', 'nombre_completo')
                ->searchable()
                ->preload()
                ->required(),    

            // 🔹 Sector: ahora un Select vinculado al modelo Sector
            Forms\Components\Select::make('sector_id')
                ->label('Sección / Taller')
                ->options(Sector::pluck('nombre', 'id')) // carga dinámica
                ->searchable()
                ->preload()
                ->required(),




            Forms\Components\Textarea::make('trabajos_efectuados')
                ->label('Trabajos efectuados'),
        ]);
    }

public static function table(Table $table): Table
{
    return $table
        ->defaultSort('numero_reporte', 'desc')
        ->columns([
            Tables\Columns\TextColumn::make('numero_reporte')
                ->label('N° Reporte')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('operario.nombre_completo')
                ->label('Operario')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('periodo_reporte')
                ->label('Período'),

            Tables\Columns\TextColumn::make('sector.nombre')
                ->label('Sector / Taller'),

            Tables\Columns\TextColumn::make('solicitante.nombre_completo')
                ->label('Solicitante'),

           /* Tables\Columns\TextColumn::make('usuario.name')
                ->label('Usuario'),*/

            Tables\Columns\TextColumn::make('estado')
                ->badge()
                ->colors([
                    'warning' => 'pendiente',
                    'success' => 'aprobado',
                    'danger'  => 'rechazado',
                ]),

            Tables\Columns\TextColumn::make('total_horas_general')
                ->label('Horas'),

            Tables\Columns\TextColumn::make('total_pago_general')
                ->label('Pago')
                ->money('ARS', true),

            Tables\Columns\TextColumn::make('fecha_reporte')
                ->date(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
            Tables\Actions\Action::make('imprimir')
                ->label('Imprimir')
                ->icon('heroicon-o-printer')
                ->url(fn ($record) => route('reportes-horas-extra.pdf', $record->id))
                ->openUrlInNewTab(),
        ]);
}



    public static function getRelations(): array
    {
        return [
            RegistroHorasRelationManager::class, // 👈 Aquí se enganchan los registros
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReportesHorasExtras::route('/'),
            'create' => Pages\CreateReportesHorasExtra::route('/create'),
            'edit' => Pages\EditReportesHorasExtra::route('/{record}/edit'),
        ];
    }
}
