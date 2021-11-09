<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<p>Hola, con fecha y hora {{$DatosEmail->fecha}} el profesor {{$DatosEmail->full_name}} ha registrado una nueva Acta de Reunión de @if ($DatosEmail->tipo == 'Proyecto de titulo') el @else la @endif {{$DatosEmail->tipo}} con titulo {{$DatosEmail->titulo}}</p>

<br>
<p>Sistema de Gestión y Administración de Documentos</p>
<p>Universidad Catolica Del Maule</p>

    
</body>
</html>