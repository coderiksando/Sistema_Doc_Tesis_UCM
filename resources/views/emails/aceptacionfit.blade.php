<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<p>Hola, con fecha y hora {{$DatosEmail->fecha}}, el {{$DatosEmail->rol}} {{$DatosEmail->full_name}} ha {{$DatosEmail->estado}} el Formulario de
    Inscripción de {{$DatosEmail->tipo}} con titulo {{$DatosEmail->titulo}}@if ($DatosEmail->motivo), el motivo por el cual fue rechazado es "{{$DatosEmail->motivo}}".
    @else
    .
    @endif
    </p>

<br>
<p>Sistema de Gestión y Administración de Documentos</p>
<p>Universidad Catolica Del Maule</p>

</body>
</html>
