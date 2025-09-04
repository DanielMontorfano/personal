<?php

namespace App\Filament\Resources\PlanillaZafraResource\Pages;

use App\Filament\Resources\PlanillaZafraResource;
use App\Models\PlanillaZafra;
use App\Models\AsignacionZafra;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreatePlanillaZafra extends CreateRecord
{
    protected static string $resource = PlanillaZafraResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        Log::info('Iniciando creación de planilla con datos:', $data);
        
        return DB::transaction(function () use ($data) {
            // Extraer las asignaciones antes de crear la planilla
            $asignaciones = $data['asignaciones'] ?? [];
            unset($data['asignaciones']);
            
            Log::info('Datos de la planilla (sin asignaciones):', $data);
            Log::info('Asignaciones a crear:', $asignaciones);
            
            // Crear la planilla
            $planilla = PlanillaZafra::create($data);
            
            Log::info('Planilla creada con ID:', ['id' => $planilla->id]);
            
            // Crear las asignaciones si existen
            if (!empty($asignaciones)) {
                foreach ($asignaciones as $asignacionData) {
                    $asignacionData['planilla_zafra_id'] = $planilla->id;
                    
                    Log::info('Creando asignación:', $asignacionData);
                    
                    $asignacion = AsignacionZafra::create($asignacionData);
                    
                    Log::info('Asignación creada con ID:', ['id' => $asignacion->id]);
                }
            }
            
            // Recargar la planilla con sus asignaciones
            $planilla->load('asignaciones');
            
            Log::info('Planilla final con asignaciones:', [
                'planilla_id' => $planilla->id,
                'total_asignaciones' => $planilla->asignaciones->count()
            ]);
            
            return $planilla;
        });
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Planilla de Zafra creada exitosamente';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Log::info('Datos del formulario antes de mutate:', $data);
        
        // Aplicar la transformación del Resource
       $transformedData = PlanillaZafraResource::transformFormData($data);
        
        Log::info('Datos transformados:', $transformedData);
        
        return $transformedData;
    }
}