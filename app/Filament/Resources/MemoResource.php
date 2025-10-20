<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemoResource\Pages;
use App\Models\Memo;
use App\Models\Solicitante;
use App\Models\Operario;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Get;
use Filament\Forms\Set;

class MemoResource extends Resource
{
    protected static ?string $model = Memo::class;
    protected static ?string $navigationLabel = 'Memos';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('numero')
                    ->label('Número')
                    ->disabled()
                    ->placeholder('Se genera al crear'),
                
                Select::make('de_solicitante_id')
                    ->label('Remitente')
                    ->options(Solicitante::query()->pluck('nombre_completo','id')->toArray())
                    ->searchable()
                    ->required(),
                
                MultiSelect::make('destinatarios')
                    ->label('Destinatarios')
                    ->options(Solicitante::query()->pluck('nombre_completo','id')->toArray())
                    ->required()
                    ->helperText('Selecciona al menos un destinatario'),
                
                MultiSelect::make('operarios')
                    ->label('Operarios mencionados')
                    ->options(Operario::query()->pluck('nombre_completo','id')->toArray())
                    ->helperText('Menciones (no destinatarios)'),
                
                DatePicker::make('fecha')
                    ->label('Fecha')
                    ->required(),
                
                TextInput::make('referencia')
                    ->label('Referencia')
                    ->required(),
                
                Forms\Components\Section::make('Cuerpo del Memo')
                    ->schema([
                        Select::make('insertar_operario')
                            ->label('Insertar operario en el texto')
                            ->placeholder('Selecciona un operario para insertar...')
                            ->options(Operario::query()->pluck('nombre_completo','id')->toArray())
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if ($state) {
                                    $operario = Operario::find($state);
                                    if ($operario) {
                                        $cuerpoActual = $get('cuerpo') ?? '';
                                        $nombreOperario = $operario->nombre_completo;
                                        
                                        // Insertar el nombre del operario al final del contenido actual
                                        $nuevoContenido = $cuerpoActual . ' ' . $nombreOperario;
                                        
                                        $set('cuerpo', $nuevoContenido);
                                    }
                                    // Resetear el select después de insertar
                                    $set('insertar_operario', null);
                                }
                            })
                            ->dehydrated(false)
                            ->helperText('El nombre se insertará al final del texto actual'),
                        
                        RichEditor::make('cuerpo')
                            ->label('Cuerpo')
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero')
                    ->label('Nro')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('remitente.nombre')
                    ->label('Remitente')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('fecha')
                    ->date()
                    ->label('Fecha')
                    ->sortable(),
                
                TextColumn::make('referencia')
                    ->limit(40)
                    ->label('Referencia')
                    ->searchable(),
                
                TextColumn::make('destinatarios_count')
                    ->label('Destinatarios')
                    ->counts('destinatarios'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('de_solicitante_id')
                    ->label('Remitente')
                    ->options(Solicitante::query()->pluck('nombre_completo','id')->toArray()),

                Tables\Filters\SelectFilter::make('operario')
                    ->label('Operario mencionado')
                    ->options(Operario::query()->pluck('nombre_completo','id')->toArray())
                    ->query(function ($query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('operarios', fn($q) => $q->where('operarios.id', $data['value']));
                        }
                    }),

                Tables\Filters\Filter::make('fecha_rango')
                    ->form([
                        DatePicker::make('fecha_desde')->label('Desde'),
                        DatePicker::make('fecha_hasta')->label('Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['fecha_desde']) {
                            $query->where('fecha', '>=', $data['fecha_desde']);
                        }
                        if ($data['fecha_hasta']) {
                            $query->where('fecha', '<=', $data['fecha_hasta']);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMemos::route('/'),
            'create' => Pages\CreateMemo::route('/create'),
            'edit' => Pages\EditMemo::route('/{record}/edit'),
            // 'view' => Pages\ViewMemo::route('/{record}'),
        ];
    }
}