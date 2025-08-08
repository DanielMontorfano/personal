<?php

namespace App\Filament\Resources\PlanillaZafraResource\Pages;

use App\Filament\Resources\PlanillaZafraResource;
use App\Models\PlanillaZafra;
use App\Models\AsignacionZafra;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditPlanillaZafra extends EditRecord
{
    protected static string $resource = PlanillaZafraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        Log::info('Datos antes de cargar al formulario:', $data);
        
        // Cargar las asignaciones desde la base de datos
        $record = $this->getRecord();
        $asignaciones = $record->asignaciones()->get()->toArray();
        
        Log::info('Asignaciones desde BD:', $asignaciones);
        
        // Agregar las asignaciones a los datos
        $data['asignaciones'] = $asignaciones;
        
        // Aplicar la carga de asignaciones del Resource
        $dataWithAsignaciones = PlanillaZafraResource::loadAsignaciones($data);
        
        Log::info('Datos después de cargar asignaciones:', $dataWithAsignaciones);
        
        return $dataWithAsignaciones;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        Log::info('Datos del formulario antes de transformar (edit):', $data);
        
        // Aplicar la transformación del Resource
        $transformedData = PlanillaZafraResource::transformFormData($data);
        
        Log::info('Datos transformados para guardar (edit):', $transformedData);
        
        return $transformedData;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        Log::info('Iniciando actualización de planilla ID: ' . $record->id, $data);
        
        return DB::transaction(function () use ($record, $data) {
            // Extraer las asignaciones antes de actualizar la planilla
            $asignaciones = $data['asignaciones'] ?? [];
            unset($data['asignaciones']);
            
            Log::info('Datos de la planilla para actualizar (sin asignaciones):', $data);
            Log::info('Asignaciones nuevas:', $asignaciones);
            
            // Actualizar la planilla principal
            $record->update($data);
            
            Log::info('Planilla actualizada, ahora manejando asignaciones...');
            
            // Eliminar todas las asignaciones existentes
            $asignacionesEliminadas = $record->asignaciones()->count();
            $record->asignaciones()->delete();
            
            Log::info('Asignaciones eliminadas:', ['count' => $asignacionesEliminadas]);
            
            // Crear las nuevas asignaciones
            if (!empty($asignaciones)) {
                foreach ($asignaciones as $asignacionData) {
                    $asignacionData['planilla_zafra_id'] = $record->id;
                    
                    Log::info('Creando nueva asignación:', $asignacionData);
                    
                    $asignacion = AsignacionZafra::create($asignacionData);
                    
                    Log::info('Nueva asignación creada con ID:', ['id' => $asignacion->id]);
                }
            }
            
            // Recargar la planilla con sus nuevas asignaciones
            $record->load('asignaciones');
            
            Log::info('Planilla actualizada final:', [
                'planilla_id' => $record->id,
                'total_asignaciones' => $record->asignaciones->count()
            ]);
            
            return $record;
        });
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Planilla de Zafra actualizada exitosamente';
    }
}