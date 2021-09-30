<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table>
        <thead>
        <tr>
            <th>Numeración</th>
            <th>Nombres</th>
            <th>Rut</th>
            <th>Año ingreso</th>
            <th>Fecha última asignatura cursada</th>
            <th>Fecha de titulación</th>
            <th>Tiempo total de titulación</th>
            <th>Teléfono</th>
            <th>Correo electrónico</th>
            <th>Facultad</th>
            <th>Carrera</th>
            <th>Profesor Guía</th>
            <th>Tipo</th>
            <th>Título</th>
            <th>Estado de aprobación</th>
            <th>Estado de avances</th>
            <!-- <th>Detalles de documentos</th> -->
            <th>Fecha última bitácora</th>
            <th>Fecha último avance </th>
            <th>Fecha creación de FID</th>
            <th>Fecha nota pendiente</th>
            <th>Fecha prorroga nota Pend.</th>
        </tr>
        </thead>
        <tbody>
        @php($count=0)
        @foreach($reportefit as $datatesis)
            @php($count++)
            @php($i=0)
            @php($large=count($datatesis->fit__user))
            @foreach($datatesis->fit__user as $fit_user)
            <tr>
                @if($i==0)
                    <td>{{ $count }}</td>
                    <td>{{ $datatesis->fit__user[$i]->user->nombres.' '.$datatesis->fit__user[$i]->user->apellidos }}</td>
                    <td>{{ $datatesis->fit__user[$i]->user->rut }}</td>
                    @if($datatesis->fit__user[$i]->user->f_ingreso)
                        <td>{{ Carbon\Carbon::parse($datatesis->fit__user[$i]->user->f_ingreso)->format('d-m-Y') }} </td>
                    @else
                        <td>No disponible</td>
                    @endif
                    @if($datatesis->fit__user[$i]->user->f_salida)
                        <td>{{ Carbon\Carbon::parse($datatesis->fit__user[$i]->user->f_salida)->format('d-m-Y') }}</td>
                    @else
                        <td>No disponible</td>
                    @endif
                    @if($datatesis->acta)
                        <td>{{ Carbon\Carbon::parse($datatesis->acta->updated_at)->format('d-m-Y') }}</td>
                        @php($difference = Carbon\Carbon::parse($datatesis->fit__user[$i]->user->f_ingreso)->diff(Carbon\Carbon::parse($datatesis->acta->updated_at)))
                        <td>{{ $difference->y.' años y '.$difference->m.' mes(es).' }}</td>
                    @else
                        <td>No disponible</td>
                        <td>No disponible</td>
                    @endif
                    <td>{{ $datatesis->fit__user[$i]->user->telefono }}</td>
                    <td>{{ $datatesis->fit__user[$i]->user->email }}</td>
                    <td>{{ $datatesis->fit__user[$i]->user->escuelas->facultad->nombre }}</td>
                    <td>{{ $datatesis->fit__user[$i]->user->escuelas->nombre }}</td>
                    <td>{{ $datatesis->user__p__guia->nombres.' '.$datatesis->user__p__guia->apellidos }}</td>
                    <td>{{ $datatesis->tipo }}</td>
                    <td>{{ substr($datatesis->titulo, 0, 40) }}</td>
                    @if($datatesis->estado == 'A')
                        <td> Aprobado </td>
                    @elseif($datatesis->estado == 'D')
                        <td> En desarrollo </td>
                    @elseif($datatesis->estado == 'R')
                        <td> Reprobado </td>
                    @endif
                    @if($datatesis->acta)
                        @php($prioridadActaAhora = Carbon\Carbon::parse($datatesis->acta->updated_at))
                    @else
                        @php($prioridadActaAhora = Carbon\Carbon::now())
                    @endif
                    @php($estadoAvances = Carbon\Carbon::parse($datatesis->updated_at)->diff($prioridadActaAhora))
                    <td>{{ count($datatesis->avances_tesis).' avances en '.$estadoAvances->m.' mes(es) y '.$estadoAvances->d.' días' }}</td>
                    <!-- <td>Detalles de documentos</td> -->
                @if(count($datatesis->bitacoras)>=1)
                    <td>{{ Carbon\Carbon::parse($datatesis->bitacoras[count($datatesis->bitacoras)-1]->updated_at)->format('d-m-Y') }}</td>
                @else
                    <td>Sin registros</td>
                @endif
                @if(count($datatesis->avances_tesis)>=1)
                    <td>{{ Carbon\Carbon::parse($datatesis->avances_tesis[count($datatesis->avances_tesis)-1]->updated_at)->format('d-m-Y') }}</td>
                @else
                    <td>Sin registros</td>
                @endif
                    <td>{{ Carbon\Carbon::parse($datatesis->created_at)->format('d-m-Y') }}</td>
                @if($datatesis->notas_pendientes)
                    @if($datatesis->notas_pendientes->fecha_autorizada)
                        <td>{{ Carbon\Carbon::parse($datatesis->notas_pendientes->fecha_autorizada)->format('d-m-Y') }}</td>
                    @else
                        <td>Sin fecha autorizada</td>
                    @endif
                    @if($datatesis->notas_pendientes->fecha_prorroga)
                        <td>{{ Carbon\Carbon::parse($datatesis->notas_pendientes->fecha_prorroga)->format('d-m-Y') }}</td>
                    @else
                        <td>Sin fecha de prórroga</td>
                    @endif
                @else
                    <td>Sin fecha autorizada</td>
                    <td>Sin fecha de prórroga</td>
                @endif
                @else
                    <td></td>
                    <td>{{ $datatesis->fit__user[$i]->user->nombres.' '.$datatesis->fit__user[$i]->user->apellidos }}</td>
                    <td>{{ $datatesis->fit__user[$i]->user->rut }}</td>
                    <td>{{ date('d-m-Y', strtotime($datatesis->fit__user[$i]->user->f_ingreso)) }} </td>
                    <td>{{ date('d-m-Y', strtotime($datatesis->fit__user[$i]->user->f_salida)) }}</td>
                    @if($datatesis->acta)
                        <td>{{ Carbon\Carbon::parse($datatesis->acta->updated_at)->format('d-m-Y') }}</td>
                        @php($difference = Carbon\Carbon::parse($datatesis->fit__user[$i]->user->f_ingreso)->diff(Carbon\Carbon::parse($datatesis->acta->updated_at)))
                        <td>{{ $difference->y.' años y '.$difference->m.' mes(es).' }}</td>
                    @else
                        <td>No disponible</td>
                        <td>No disponible</td>
                    @endif
                    <td>{{ $datatesis->fit__user[$i]->user->telefono }}</td>
                    <td>{{ $datatesis->fit__user[$i]->user->email }}</td>
                    <td>{{ $datatesis->fit__user[$i]->user->escuelas->facultad->nombre }}</td>
                    <td>{{ $datatesis->fit__user[$i]->user->escuelas->nombre }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                @endif
                @php($i++)
            </tr>
            @endforeach
            <tr></tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
