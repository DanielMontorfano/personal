<!DOCTYPE html>
<html>
<head>
    <title>Planilla de Zafra - {{ $planilla->zafra->nombre }} {{ date('Y', strtotime($planilla->fecha)) }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 15px; }
        .title { font-size: 14pt; font-weight: bold; }
        .subtitle { font-size: 11pt; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 3px 5px; }
        th { background-color: #f2f2f2; text-align: center; }
        .turno-header { text-align: center; font-weight: bold; }
        .funcion { font-weight: bold; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">ZAFRA {{ strtoupper($planilla->zafra->nombre) }} {{ date('Y', strtotime($planilla->fecha)) }}</div>
        <div class="subtitle">
            Planilla N°: {{ $planilla->numero }} | 
            Fecha: {{ date('d/m/Y', strtotime($planilla->fecha)) }} | 
            Solicitante: {{ $planilla->solicitante->nombre_completo }} | 
            Sector: {{ $planilla->sector?->nombre ?? 'No especificado' }}
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 20%">FUNCIÓN</th>
                <th colspan="5" class="turno-header">MAÑANA</th>
                <th colspan="5" class="turno-header">TARDE</th>
                <th colspan="5" class="turno-header">NOCHE</th>
            </tr>
            <tr>
                <!-- Mañana -->
                <th>No#</th>
                <th>Nombre</th>
                <th>CAT</th>
                <th>MF</th>
                <th>GCT</th>
                
                <!-- Tarde -->
                <th>No#</th>
                <th>Nombre</th>
                <th>CAT</th>
                <th>MF</th>
                <th>GCT</th>
                
                <!-- Noche -->
                <th>No#</th>
                <th>Nombre</th>
                <th>CAT</th>
                <th>MF</th>
                <th>GCT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($planilla->asignaciones->groupBy('puesto_id') as $puestoId => $asignaciones)
                @php
                    $puesto = $asignaciones->first()->puesto;
                    
                    // Buscamos asignaciones por turno
                    $manana = $asignaciones->where('turno', 'Mañana')->first();
                    $tarde = $asignaciones->where('turno', 'Tarde')->first();
                    $noche = $asignaciones->where('turno', 'Noche')->first();
                @endphp
                
                <tr>
                    <td class="funcion">{{ $puesto->nombre }}</td>
                    
                    <!-- Turno Mañana -->
                    <td class="text-center">{{ $manana->operario->legajo ?? '' }}</td>
                    <td>{{ $manana->operario->nombre_completo ?? '' }}</td>
                    <td class="text-center">{{ $manana->categoria_operario ?? '' }}</td>
                    <td class="text-center">{{ $manana->categoria_mayor ?? '' }}</td>
                    <td class="text-center">{{ $manana->categoria_puesto ?? '' }}</td>
                    
                    <!-- Turno Tarde -->
                    <td class="text-center">{{ $tarde->operario->legajo ?? '' }}</td>
                    <td>{{ $tarde->operario->nombre_completo ?? '' }}</td>
                    <td class="text-center">{{ $tarde->categoria_operario ?? '' }}</td>
                    <td class="text-center">{{ $tarde->categoria_mayor ?? '' }}</td>
                    <td class="text-center">{{ $tarde->categoria_puesto ?? '' }}</td>
                    
                    <!-- Turno Noche -->
                    <td class="text-center">{{ $noche->operario->legajo ?? '' }}</td>
                    <td>{{ $noche->operario->nombre_completo ?? '' }}</td>
                    <td class="text-center">{{ $noche->categoria_operario ?? '' }}</td>
                    <td class="text-center">{{ $noche->categoria_mayor ?? '' }}</td>
                    <td class="text-center">{{ $noche->categoria_puesto ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>