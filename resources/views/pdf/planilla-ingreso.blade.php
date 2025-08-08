<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Planilla de Ingreso</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .info {
            margin-bottom: 15px;
        }

        .info strong {
            display: inline-block;
            width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        table, th, td {
            border: 1px solid #555;
        }

        th, td {
            padding: 4px;
            text-align: left;
        }

        .firma {
            margin-top: 30px;
        }

        .firma span {
            display: block;
            margin-top: 10px;
            border-top: 1px solid #000;
            width: 250px;
        }
    </style>
</head>
<body>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 12px;">
    <tr>
        <!-- Columna 1: Logo -->
        <td style="width: 25%; border: 1px solid #555; text-align: center; vertical-align: middle;">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="max-height: 100px;">
        </td>

        <!-- Columna 2: Título doble -->
        <td style="width: 50%; border: 1px solid #555; text-align: center; vertical-align: middle;">
            <strong style="display: block; font-size: 16px;">Protocolo de ingreso de personal transitorio</strong>
            <span style="font-size: 13px;">Planilla de registro</span>
        </td>

        <!-- Columna 3: Divisiones verticales simuladas -->
        <td style="width: 25%; border: 1px solid #555; text-align: center; vertical-align: top; padding: 0;">
            <div style="text-align: left;border-bottom: 1px solid #555; padding: 4px;">RHRE03.V01</div>
            <div style="text-align: left;border-bottom: 1px solid #555; padding: 4px;">Fecha de Vigencia : </div>
            <div style="text-align: left;border-bottom: 1px solid #555; padding: 4px;">Fecha de Revision:</div>
            <div style="text-align: left;padding: 4px;">Planilla Nº: {{ $planilla->numero ?? 'N/A' }}</div>
        </td>
    </tr>
</table>

    <table>
    <thead>
<tr>
    <th colspan="13" style="text-align: center; font-size: 18px; font-weight: normal; ">
        <strong>Planilla de personal</strong>&nbsp;&nbsp;&nbsp;
        {{ \Carbon\Carbon::parse($planilla->fecha)->format('d/m/Y') }} 
    </th>
</tr>
<tr>
    <th rowspan="2" style="width: 2%; text-align: center">N°</th>
    <th colspan="4" style="text-align: center; width: 45%;">
        <strong>A</strong> - Sección / AREA: {{ $planilla->solicitante->sector }}
    </th>
    <th colspan="2" style="text-align: center; width: 14%;"><strong>B</strong> - Medicina Lab.</th>
    <th colspan="1" style="text-align: center; width: 7%;"><strong>C</strong> - HyS</th>
    <th colspan="5" style="text-align: center; width: 30%;"><strong>D</strong> - Dpto. Personal</th>
</tr>
<tr>
    <th style="width: 26%;">Nombre y Apellido</th>     
    <th style="width: 5%;">Legajo</th>
    <th style="width: 7%;">Sector</th>
    <th style="width: 7%;">Puesto</th>
    <th style="width: 7%;">Examen <br> físico</th>
    <th style="width: 7%;">Fecha</th>
    <th style="width: 7%;">Inducción</th>
    <th style="width: 7%;">Contacto</th>
    <th style="width: 7%;">Alta<br>temprana</th> 
    <th style="width: 7%;">Ingreso</th>
    <th style="width: 7%;">Baja</th>
    <th style="width: 7%;">Observaciones</th>
</tr>
</thead>



        <tbody>
            @foreach ($planilla->ingresos as $index => $ingreso)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $ingreso->operario->nombre_completo }}</td>
                    <td>{{ $ingreso->operario->legajo }}</td>
                    <td>{{ $ingreso->operario->sector ?? '-' }}</td>
                    <td>{{ $ingreso->operario->tarea }}</td>
                    <td>{{ $ingreso->examenMedico?->resultado ? ucfirst(str_replace('_', ' ', $ingreso->examenMedico->resultado)) : '-' }}</td>
                    <td>
                        @if($ingreso->examenMedico?->fecha_examen)
                            {{ \Carbon\Carbon::parse($ingreso->examenMedico->fecha_examen)->format('d/m/Y') }}
                        @endif
                    </td>
                    <td>
                        {{ $ingreso->induccion?->responsable ?? '-' }}
                        @if($ingreso->induccion?->fecha_induccion)
                            <br>{{ \Carbon\Carbon::parse($ingreso->induccion->fecha_induccion)->format('d/m/Y') }}
                        @endif
                    </td> 
                    <td></td>
                    <td></td>
                    <td>{{ \Carbon\Carbon::parse($ingreso->fecha_ingreso)->format('d/m/Y') }}</td>
                    <td>{{ $ingreso->fecha_baja ? \Carbon\Carbon::parse($ingreso->fecha_baja)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $ingreso->observaciones ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

   
    <table style="width: 80%; border-collapse: collapse; margin-top: 15px;margin-left: auto; margin-right: auto;">
    <thead>
        <tr>
            <th colspan="2" style="width: 30%; border: 1px solid #555; text-align: center;">DETALLE DE APROBACIÓN</th>
            <th style="width: 20%; border: 1px solid #555; text-align: center;">FIRMA</th>
            <th style="width: 30%; border: 1px solid #555; text-align: center;">ACLARACIÓN</th>
            <th style="width: 20%; border: 1px solid #555; text-align: center; vertical-align: middle;">Autorización Ingreso <br> Jefe Personal</th>
        </tr>
    </thead>
    <tbody>
        @php
            $detalles = [
                'A' => 'Jefe de sector',
                'B' => 'Jefe de Planta',
                'C' => 'Departamento de Medicina Laboral',
                'D' => 'Departamento de HyS',
                'E' => 'Departamento Personal',
            ];
        @endphp

        @foreach (['A', 'B', 'C', 'D', 'E'] as $i => $letra)
    <tr>
        <td style="width: 3%; border: 1px solid #555; text-align: center;">{{ $letra }}</td>
        <td style="width: 32%; border: 1px solid #555;">{{ $detalles[$letra] }}</td>
        <td style="width: 15%; border: 1px solid #555;"></td>
        <td style="width: 30%; border: 1px solid #555;">
            @if ($letra === 'A')
                {{ $planilla->solicitante->nombre_completo }}
            @endif
        </td>

        @if ($i === 0)
            <td rowspan="2" style="width: 20%; border: 1px solid #555;"></td>
        @elseif ($i === 1)
            {{-- omitimos la celda porque está ocupada por rowspan --}}
        @else
            <td style="width: 20%; border: 1px solid #555;"></td>
        @endif
    </tr>
@endforeach
    </tbody>
</table>




    <div class="info">
          <p>Nota: {{ $planilla->observaciones }}</p> 
    </div>
</body>
</html>
