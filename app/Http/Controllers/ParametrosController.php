<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Parametro;
use Debugbar;

class ParametrosController extends Controller
{
    public function getParametros(Request $request){
        if(!$request->ajax()) return redirect('/');

        $arrayParametros = $request->params;
        $arrayResultado = [];
        foreach ($arrayParametros as $nombreParametro) {
            $parametros = Parametro::where('parametro', $nombreParametro)->get()->pluck('valor')->all();
            array_push($arrayResultado, $parametros);
        }

        return $arrayResultado;
    }

    public function setParametros(Request $request){
        $maxStudentNumber           = $request->MaxStudentNumber;
        $avancesTesisSize           = $request->AvancesTesisSize;
        $actaSize                   = $request->ActaSize;
        $constanciaSize             = $request->ConstanciaSize;
        $avancesTesisFormato        = $request->AvancesTesisFormato;
        $actaFormato                = $request->ActaFormato;
        $constanciaFormato          = $request->ConstanciaFormato;
        $habilitarRegistro          = $request->HabilitarRegistro;
        $terminoAbreviado           = $request->TerminoAbreviado;
        $terminoExtendido           = $request->TerminoExtendido;
        $habilitarEmails            = $request->HabilitarEmails;
        $emailAceptarFormulario     = $request->emailAceptarFormulario;
        $emailSubirAvance           = $request->emailSubirAvance;
        $emailCrearActaReunion      = $request->emailCrearActaReunion;
        $emailNotaFinal             = $request->emailNotaFinal;
        $emailNotaPendiente         = $request->emailNotaPendiente;
        $emailCrearFID              = $request->emailCrearFID;
        

        Parametro::where('parametro', 'MaxStudentNumber')->delete();
        Parametro::create(['parametro'=>'MaxStudentNumber', 'valor' => $maxStudentNumber]);
        Parametro::where('parametro', 'AvancesTesisSize')->delete();
        Parametro::create(['parametro'=>'AvancesTesisSize', 'valor' => $avancesTesisSize]);
        Parametro::where('parametro', 'ActaSize')->delete();
        Parametro::create(['parametro'=>'ActaSize', 'valor' => $actaSize]);
        Parametro::where('parametro', 'ConstanciaSize')->delete();
        Parametro::create(['parametro'=>'ConstanciaSize', 'valor' => $constanciaSize]);
        Parametro::where('parametro', 'HabilitarRegistro')->delete();
        Parametro::create(['parametro'=>'HabilitarRegistro', 'valor' => $habilitarRegistro]);
        Parametro::where('parametro', 'TerminoDeTitulo')->delete();
        Parametro::create(['parametro'=>'TerminoDeTitulo', 'valor' => $terminoAbreviado]);
        Parametro::where('parametro', 'TerminoDeTituloExtendido')->delete();
        Parametro::create(['parametro'=>'TerminoDeTituloExtendido', 'valor' => $terminoExtendido]);
        Parametro::where('parametro', 'AvancesTesisFormato')->delete();
        Parametro::where('parametro', 'ActaFormato')->delete();
        Parametro::where('parametro', 'ConstanciaFormato')->delete();
        Parametro::where('parametro', 'HabilitarEmails')->delete();
        Parametro::create(['parametro'=>'HabilitarEmails', 'valor' => $habilitarEmails]);
        Parametro::where('parametro', 'emailAceptarFormulario')->delete();
        Parametro::create(['parametro'=>'emailAceptarFormulario', 'valor' => $emailAceptarFormulario]);
        Parametro::where('parametro', 'emailSubirAvance')->delete();
        Parametro::create(['parametro'=>'emailSubirAvance', 'valor' => $emailSubirAvance]);
        Parametro::where('parametro', 'emailCrearActaReunion')->delete();
        Parametro::create(['parametro'=>'emailCrearActaReunion', 'valor' => $emailCrearActaReunion]);
        Parametro::where('parametro', 'emailNotaFinal')->delete();
        Parametro::create(['parametro'=>'emailNotaFinal', 'valor' => $emailNotaFinal]);
        Parametro::where('parametro', 'emailNotaPendiente')->delete();
        Parametro::create(['parametro'=>'emailNotaPendiente', 'valor' => $emailNotaPendiente]);
        Parametro::where('parametro', 'emailCrearFID')->delete();
        Parametro::create(['parametro'=>'emailCrearFID', 'valor' => $emailCrearFID]);

        foreach ($avancesTesisFormato as $formato) {
            Parametro::create([
                'parametro'=>'AvancesTesisFormato',
                'valor'=> $formato
            ]);
        }
        foreach ($actaFormato as $formato) {
            Parametro::create([
                'parametro'=>'ActaFormato',
                'valor'=> $formato
            ]);
        }
        foreach ($constanciaFormato as $formato) {
            Parametro::create([
                'parametro'=>'ConstanciaFormato',
                'valor'=> $formato
            ]);
        }
        return 200;
    }
}
