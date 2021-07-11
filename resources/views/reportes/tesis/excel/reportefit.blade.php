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
            <th>Fecha última asignatura cursada</th>
            <th>Profesor Guía</th>
            <th>Tipo</th>
            <th>Fecha última bitácora</th>
            <th>Comentario bitácora</th>
            <th>Fecha último avance </th>
            <th>Estado</th>
            <th>Fecha creación del formulario</th>
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
                    <td>{{ $datatesis->fit__user[$i]->user->f_salida }}</td>
                    <td>{{ $datatesis->user__p__guia->nombres.' '.$datatesis->user__p__guia->apellidos }}</td>
                    <td>{{ $datatesis->tipo }}</td>
                @if($datatesis->bitacoras)
                    <td>{{ $datatesis->bitacoras[count($datatesis->bitacoras)] }}</td>
                    <td>{{ $datatesis->bitacoras[count($datatesis->bitacoras)] }}</td>
                @else
                    <td></td>
                    <td></td>
                @endif
                @if($datatesis->avances_tesis)
                    <td>{{ $datatesis->avances_tesis[count($datatesis->avances_tesis)] }}</td>
                @else
                    <td></td>
                @endif
                    <td>{{ $datatesis->estado }}</td>
                    <td>{{ $datatesis->created_at }}</td>
                @if($datatesis->notas_pendientes)
                    <td>{{ $datatesis->notas_pendientes->fecha_autorizada }}</td>
                    <td>{{ $datatesis->notas_pendientes->fecha_autorizada }}</td>
                @else
                    <td></td>
                    <td></td>
                @endif
                @else
                    <td></td>
                    <td>{{ $datatesis->fit__user[$i]->user->nombres.' '.$datatesis->fit__user[$i]->user->apellidos }}</td>
                    <td>{{ $datatesis->fit__user[$i]->user->rut }}</td>
                    <td>{{ $datatesis->fit__user[$i]->user->f_salida }}</td>
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
