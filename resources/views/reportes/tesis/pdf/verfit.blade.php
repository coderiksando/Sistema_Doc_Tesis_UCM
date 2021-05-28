<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>fit del alumno {{$datosfit->Fit_User[0]->User->nombres}} {{$datosfit->Fit_User[0]->User->apellidos}} </title>
    <style>
        @page{
            margin: 1.3rem;
            margin-top:1.9rem;
            padding: 1rem;
        }
        body {
            margin: 0px;
        }
        * {
            font-family: "Times New Roman", Times, serif;
        }
        .cabecera {
            background-color: #FEFEFE;
            color: #000000;
            padding: 2rem;
            padding-top: .2rem;
            padding-bottom: 0px;
        }
        .cabecera .logo{
            margin: 5px;
        }
        .cabecera table {
            padding: 1px;
            margin-bottom: .2rem;
        }
        table {
            font-size: small;
        }
        tfoot tr td{
            font-weight: bold;
            font-size: x-small;
        }
    </style>
</head>
<body>
    <div class="cabecera">
        <table width="100%" cellspacing="0" cellspacing="1" align="center">
            <tr>
                <td style="text-align: center;">UNIVERSIDAD CATOLICA DEL MAULE</td>
            </tr>
            <tr>
                <td style="text-align: center;">FACULTAD DE CIENCIAS DE LA INGENIERIA</td>
            </tr>
            <tr>
                <td style="text-align: center;">ESCUELA DE {{$datosfit->user_p_guia->escuelas->nombre}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">FORMULARIO DE INSCRIPCION DE TEMA DE TESIS Y MEMORIAS DE TITULO</td>
            </tr>
        </table>
        <br>
        <br>

        <table width="100%" cellspacing="0" cellspacing="1" align="center">
            <tr>
                <td> <strong>NOMBRE COMPLETO:</strong>  {{$datosfit->Fit_User[0]->User->nombres}} {{$datosfit->Fit_User[0]->User->apellidos}} </td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td> <strong>RUT:</strong>  {{$datosfit->Fit_User[0]->User->rut}}</td>
                <td> <strong>AÑO DE INGRESO:</strong>  {{$datosfit->Fit_User[0]->User->birthday}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td> <strong>CARRERA:</strong>  {{$datosfit->Fit_User[0]->User->escuelas->nombre}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td> <strong>EMAIL:</strong>  {{$datosfit->Fit_User[0]->User->email}}</td>
                <td> <strong>TELEFONO:</strong>  {{$datosfit->Fit_User[0]->User->telefono}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellspacing="1" align="center">
            <tr>
                <td style="text-align: justify;"> <strong>NOMBRE TESIS/MEMORIA:</strong>  {{$datosfit->titulo}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: justify;"> <strong>BREVE DESCRIPCION DEL TEMA:</strong>  {{$datosfit->descripcion}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: justify;"> <strong>OBJETIVOS:</strong>  {{$datosfit->objetivo}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: justify;"> <strong>CONTRIBUCION ESPERADA:</strong>  {{$datosfit->contribucion}}</td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellspacing="1" align="center">
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">FIRMA ALUMNO</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td>FECHA:..../..../.....-</td>
            </tr>
        </table>

        <div style="page-break-after:always;"></div>

        <table width="100%" cellspacing="0" cellspacing="1" align="center">
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td> <strong>PROFESOR GUIA:</strong>  {{$datosfit->user_p_guia->nombres}} {{$datosfit->user_p_guia->apellidos}}</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            @if ($datosfit->Comisiones)
                <tr>
                    <td> <strong>COMISION SUGERIDA POR EL PROFESOR GUIA:</strong> </td>
                </tr>
                <tr>
                    <td style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td> <strong>1.-</strong>  {{$datosfit->Comisiones}}</td>
                </tr>
                <tr>
                    <td style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td> <strong>2.-</strong>  {{$datosfit->Comisiones}}</td>
                </tr>
                <tr>
                    <td style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td> <strong>3.-(EXTERNOS U OTROS SI ES REQUERIDO. INDICAR CORREOS E INSTITUCION EN CASO DE SER EXTERNO):</strong></td>
                </tr>
                <tr>
                    <td style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td><strong>NOMBRE:</strong> {{$datosfit->Comisiones}} </td>
                </tr>
                <tr>
                    <td style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td> <strong>CORREO:</strong>  {{$datosfit->Comisiones}}</td>
                </tr>
                <tr>
                    <td style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td> <strong>INSTITUCION:</strong>  {{$datosfit->Comisiones}}</td>
                </tr>
                <tr>
                    <td style="text-align: center;">&nbsp;</td>
                </tr>
            @else
                <tr>
                    <td> <strong>COMISION SUGERIDA POR EL PROFESOR GUIA (Por establecer):</strong> </td>
                </tr>
                <tr>
                    <td style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td> <strong>1.-</strong>____________________________________________________________________________________________</td>
                </tr>
                <tr>
                    <td style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td> <strong>2.-</strong>____________________________________________________________________________________________</td>
                </tr>
                <tr>
                    <td style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td> <strong>3.-(EXTERNOS U OTROS SI ES REQUERIDO. INDICAR CORREOS E INSTITUCION EN CASO DE SER EXTERNO):</strong></td>
                </tr>
                <tr>
                    <td style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td><strong>NOMBRE:</strong>____________________________________________________________________________________</td>
                </tr>
                <tr>
                    <td style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td> <strong>CORREO:</strong>____________________________________________________________________________________</td>
                </tr>
                <tr>
                    <td style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td> <strong>INSTITUCION:</strong>________________________________________________________________________________</td>
                </tr>
                <tr>
                    <td style="text-align: center;">&nbsp;</td>
                </tr>
            @endif
        </table>
        <table width="100%" cellspacing="0" cellspacing="1" align="center">
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">FIRMA PROFESOR GUIA</td>
                <td style="text-align: center;">FIRMA DIRECTOR DE ESCUELA</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">FECHA:...../...../......-</td>
                <td style="text-align: center;">FECHA:...../...../......-</td>
            </tr>
        </table>
        <br>
        <table width="100%" cellspacing="0" cellspacing="1" align="center">

            <tr>
                <td style="text-align: center;">*************************************************************************</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">(A COMPLETAR POR EL DIRECTOR DEL DEPARTAMENTO)</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellspacing="1" align="center">
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>OBSERVACIONES DEL DIRECTOR DE DEPARTAMENTO:</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td style="text-align: left;">FIRMA DIRECTOR DEL DEPARTAMENTO</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td style="text-align: left;">FECHA:...../...../......-</td>
            </tr>
        </table>
    </div>

</body>
</html>
