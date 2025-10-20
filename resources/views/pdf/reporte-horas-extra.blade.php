<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 15px 20px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9.5px;
            color: #000;
        }

        .titulo {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .subtitulo {
            text-align: center;
            font-size: 10px;
            margin-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 0.5px solid #000;
            padding: 3px 4px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .firmas {
            width: 100%;
            margin-top: 18px;
            font-size: 9px;
        }

        .firmas td {
            text-align: center;
            border: none;
            padding-top: 12px;
        }

        .page-break { page-break-after: always; }
        .text-left { text-align: left; }

        /* Ajustes de ancho adaptados a A4 horizontal */
        .w-n { width: 25px; }
        .w-nombre { width: 145px; text-align: left; }
        .w-legajo { width: 45px; }
        .w-sector { width: 80px; text-align: left; }
        .w-puesto { width: 85px; text-align: left; }
        .w-fecha { width: 60px; }
        .w-hora { width: 45px; }
        .w-obs { text-align: left; }
    </style>
</head>
<body>

@for ($copia = 1; $copia <= 2; $copia++)
    <div>
        <div class="titulo">
            Planilla de Registro HORAS EXTRAS {{ $copia === 1 ? '(Original)' : '(Copia)' }}
        </div>

        <div class="subtitulo">
            Sector: {{ $reporte->sector->nombre ?? '' }} —
            Fecha: {{ \Carbon\Carbon::parse($reporte->fecha_reporte)->format('d/m/Y') }}
        </div>

        <table>
            <thead>
                <tr>
                    <th class="w-n">N°</th>
                    <th class="w-nombre">Nombre y Apellido</th>
                    <th class="w-legajo">Legajo</th>
                    <th class="w-sector">Sector</th>
                    <th class="w-puesto">Puesto</th>
                    <th class="w-fecha">Fecha</th>
                    <th class="w-hora">De</th>
                    <th class="w-hora">A</th>
                    <th class="w-obs">Observación</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $registros = $reporte->registros;
                    $totalFilas = 14; // cantidad de filas visibles (ideal para A4 horizontal)
                    $faltan = max(0, $totalFilas - $registros->count());
                @endphp

                {{-- Registros reales --}}
                @foreach($registros as $i => $r)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="text-left">{{ $r->operario->nombre_completo ?? '' }}</td>
                        <td>{{ $r->operario->legajo ?? '' }}</td>
                        <td class="text-left">{{ $r->operario->sector ?? '' }}</td>

                        {{-- 🔹 Ahora el puesto se obtiene desde registro_horas.puesto_ocupado --}}
                        <td class="text-left">{{ $r->puestoOcupado->nombre ?? '' }}</td>

                        <td>{{ optional($r->fecha_trabajada)->format('d/m/y') }}</td>
                        <td>{{ $r->hora_inicio ?? '' }}</td>
                        <td>{{ $r->hora_fin ?? '' }}</td>
                        <td class="text-left">{{ $r->actividad_espec ?? '' }}</td>
                    </tr>
                @endforeach

                {{-- Filas vacías para mantener formato --}}
                @for($i = 0; $i < $faltan; $i++)
                    <tr>
                        <td>&nbsp;</td><td></td><td></td><td></td>
                        <td></td><td></td><td></td><td></td><td></td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <table class="firmas">
            <tr>
                <td>_________________________<br>Visto Bueno</td>
                <td>_________________________<br>Firma del Solicitante</td>
            </tr>
        </table>
    </div>

    @if($copia === 1)
        <div class="page-break"></div>
    @endif
@endfor

</body>
</html>
