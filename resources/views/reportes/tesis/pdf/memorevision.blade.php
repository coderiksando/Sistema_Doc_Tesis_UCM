<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   
    <title>Memorandum revision alumno {{$datosmemo->alumnos[0]->nombres.' '.$datosmemo->alumnos[0]->apellidos}}</title>
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
            padding: 2.5rem;
            padding-top: .2.5rem;
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
    
        <table width="90%" cellspacing="0" cellspacing="1" align="center">

            <tr>
                <td WIDTH="200">
                <img src="{{$logo}}" alt="Logo UCM" class="logo", width="200" height="80">
                </td>
                <td >FACULTAD DE CIENCIAS DE LA INGENIERIA <br> ESCUELA DE {{$datosmemo->User_P_Guia->Escuelas->nombre}}</td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellspacing="1" align="center">
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">MEMORANDUM REVISION DE TESIS</td>
            </tr>
        </table>
        <br>
        <br>
       
        <table width="90%" cellspacing="1" cellspacing="1" align="center">
            <tr>
                <td width="50"><b>A  :  </b></td>
                <td>
                @if($datosmemo->Comisiones)
                <b>{{$datosmemo->Comisiones->UserP1->nombres.' '. $datosmemo->Comisiones->UserP1->apellidos}} <br> 
                    @if($datosmemo->Comisiones->UserP2)
                    {{$datosmemo->Comisiones->UserP2->nombres.' '.$datosmemo->Comisiones->UserP2->apellidos}}</b>
                    @endif
                @endif
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    @if($datosmemo->Comisiones)
                        @if($datosmemo->Comisiones->p_externo)
                            <b >{{$datosmemo->Comisiones->p_externo}}</b><br>
                        @endif
                    Profesores de comisión
                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">DE :</td>
                <td><b>MG. Roberto Ahumada Garcia</b> <br>Coordinador de Tesis y Memoria</td>
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Ref. </td>
                @if(count($datosmemo->alumnos) == 1)
                <td>Envia tesis a revision del alumno {{$datosmemo->alumnos[0]->nombres.' '.$datosmemo->alumnos[0]->apellidos}}</td>
                @else
                <td>Envia tesis a revision de los alumnos
                    @foreach ($datosmemo->alumnos as $alumno)
                        @if($loop->last)
                            {{'y '.$alumno->nombres.' '.$alumno->apellidos}}
                        @elseif($loop->first)
                            {{$alumno->nombres.' '.$alumno->apellidos}}
                        @else
                            {{', '.$alumno->nombres.' '.$alumno->apellidos}}
                        @endif
                    @endforeach
                </td>
                @endif
            </tr>
            <tr>
                <td style="text-align: center;">&nbsp;</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Fecha :</td>
                <td>TALCA, {{$datosmemo->fechainicial}}</td>
            </tr>
            <br>
        </table>

            <table width="90%" cellspacing="0" cellspacing="1" align="center">
                <tr>
                    <td>__________________________________________________________________________________________</td>
                </tr>
                <tr>
                    <td>Estimado Docente:</td>
                </tr>
                <tr>
                    <td style="text-align: center;">&nbsp;</td>
                </tr>
                <tr>
                    <td>
                        <div style="text-align: justify; text-justify: inter-word;">
                            Junto con saludarle, y como integrante de la comision de examen de Titulo <b>"{{$datosmemo->titulo}}"</b>, 
                            @if(count($datosmemo->alumnos) == 1)
                                del alumno {{$datosmemo->alumnos[0]->nombres.' '.$datosmemo->alumnos[0]->apellidos}}
                            @else
                                de los alumnos
                                @foreach ($datosmemo->alumnos as $alumno)
                                    @if($loop->last)
                                        {{'y '.$alumno->nombres.' '.$alumno->apellidos.', Rut: '.$alumno->rut}}
                                    @elseif($loop->first)
                                        {{$alumno->nombres.' '.$alumno->apellidos.', Rut: '.$alumno->rut}}
                                    @else
                                        {{', '.$alumno->nombres.' '.$alumno->apellidos.', Rut: '.$alumno->rut}}
                                    @endif
                                @endforeach
                            @endif
                            <span>, quisiera solicitar a Ud. que una vez recepcionado el documento, en el plazo de 15 dias habiles ({{$datosmemo->fechafinal}}), entregue el informe de evaluacion de este trabajo al profesor guia, {{$datosmemo->User_P_Guia->nombres.' '.$datosmemo->User_P_Guia->apellidos}}.</span>
                        </div>
                    </td>
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
                    <td style="text-align: left;">A la espera de una favorable recepción y respuesta, saluda atentamente a usted,</td>
                </tr>
            </table>
            <table width="100%" cellspacing="0" cellspacing="1" align="center">
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
                    <td><b>_________________________________</b></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td style="text-align: left; font-weight: bold;">MG.-ROBERTO AHUMADA GARCIA</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td style="text-align: left; font-weight: bold;">Coordinador de Tesis y Memorias</td>
                </tr>
            </table>

    </div>
    
</body>
</html>