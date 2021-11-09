<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<p>Hola, con fecha y hora {{$DatosEmail->fecha}} se ha registrado la nota final del alumno {{$DatosEmail->full_name}} el cual
    @if($DatosEmail->nota) {{$DatosEmail->estadonota}} con nota final de {{$DatosEmail->tipo}} {{$DatosEmail->nota}}. @else {{$DatosEmail->estadonota}}. @endif </p>

<br>
<p>Sistema de Gestión y Administración de Documentos</p>
<p>Universidad Catolica Del Maule</p>

    
</body>
</html>