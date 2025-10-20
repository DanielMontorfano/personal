<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 15px 35px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            width: 16cm;
            margin: auto;
        }

        .contenedor {
            border: 1px solid #000;
            padding: 10px 15px;
            margin-bottom: 10mm; /* 👈 espacio entre la copia superior e inferior */
            display: flex;
            flex-direction: column;
        }

        .titulo {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 0.6px solid #000;
            padding: 3px 4px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .encabezado td {
            border: none;
            text-align: left;
            padding: 2px 0;
        }

        .firmas {
            width: 100%;
            margin-top: 20mm; /* 2 cm debajo de la tabla */
        }

        .firmas td {
            border: none;
            text-align: center;
            padding-top: 10px;
        }

        .pie-espaciado {
            height: 10mm; /* 1 cm exacto debajo de las firmas */
        }

        .small {
            font-size: 9px;
        }
    </style>
</head>
<body>

@for ($copia = 1; $copia <= 2; $copia++)
    <div class="contenedor">
        <div class="titulo">HORAS EXTRAS {{ $copia == 1 ? '(Original)' : '(Copia)' }}</div>

        <table class="encabezado">
            <tr>
                <td><strong>Sección:</strong> {{ $reporte->sector->nombre ?? '' }}</td>
                <td><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reporte->fecha_reporte)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Solicitante:</strong> {{ $reporte->solicitante->nombre_completo ?? '' }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Trabajos efectuados:</strong> {{ $reporte->trabajos_efectuados }}</td>
            </tr>
        </table>

        <br>

        <table>
            <thead>
                <tr class="small">
                    <th>Fecha</th>
                    <th>Legajo</th>
                    <th>Apellido y Nombre</th>
                    <th>De</th>
                    <th>A</th>
                    <th>Hs</th>
                    <th>%</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $registros = $reporte->registros;
                    $total = 10;
                    $faltan = $total - $registros->count();
                @endphp

                {{-- Registros reales --}}
                @foreach($registros as $r)
                <tr class="small">
                    <td>{{ \Carbon\Carbon::parse($r->fecha_trabajada)->format('d/m/y') }}</td>
                    <td>{{ $reporte->operario->legajo ?? '' }}</td>
                    <td>{{ $reporte->operario->nombre_completo ?? '' }}</td>
                    <td>{{ $r->hora_inicio }}</td>
                    <td>{{ $r->hora_fin }}</td>
                    <td>{{ number_format($r->total_horas, 2, ',', '.') }}</td>
                    <td>
                        @switch($r->tipo_hora_extra)
                            @case('normal') 50% @break
                            @case('nocturna') 100% @break
                            @case('feriado') 150% @break
                            @default -
                        @endswitch
                    </td>
                </tr>
                @endforeach

                {{-- Filas vacías para completar hasta 10 --}}
                @for($i = 0; $i < $faltan; $i++)
                <tr class="small">
                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                    <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                </tr>
                @endfor
            </tbody>
        </table>

        {{-- 👇 Bloque de firmas (2 cm debajo de la tabla) --}}
        <table class="firmas">
            <tr>
                <td>V/B</td>
                <td>Firma del Solicitante</td>
            </tr>
        </table>

        <div class="pie-espaciado"></div>
    </div>
@endfor

</body>
</html>
