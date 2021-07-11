<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Parametro;

class ParametrosController extends Controller
{
    public function getParametros(Request $request){
        //if(!$request->ajax()) return redirect('/');

        $arrayParametros = $request->params;
        $arrayResultado = [];
        foreach ($arrayParametros as $nombreParametro) {
            $parametros = Parametro::where('parametro', $nombreParametro)->get()->pluck('valor')->all();
            array_push($arrayResultado, $parametros);
        }

        return $arrayResultado;
    }

    public function setParametros(Request $request){
        $maxStudentNumber    = $request->MaxStudentNumber;
        $avancesTesisSize    = $request->AvancesTesisSize;
        $actaSize            = $request->ActaSize;
        $constanciaSize      = $request->ConstanciaSize;
        $avancesTesisFormato = $request->AvancesTesisFormato;
        $actaFormato         = $request->ActaFormato;
        $constanciaFormato   = $request->ConstanciaFormato;

        Parametro::where('parametro', 'MaxStudentNumber')->delete();
        Parametro::create(['parametro'=>'MaxStudentNumber', 'valor' => $maxStudentNumber]);
        Parametro::where('parametro', 'AvancesTesisSize')->delete();
        Parametro::create(['parametro'=>'AvancesTesisSize', 'valor' => $avancesTesisSize]);
        Parametro::where('parametro', 'ActaSize')->delete();
        Parametro::create(['parametro'=>'ActaSize', 'valor' => $actaSize]);
        Parametro::where('parametro', 'ConstanciaSize')->delete();
        Parametro::create(['parametro'=>'ConstanciaSize', 'valor' => $constanciaSize]);

        Parametro::where('parametro', 'AvancesTesisFormato')->delete();
        Parametro::where('parametro', 'ActaFormato')->delete();
        Parametro::where('parametro', 'ConstanciaFormato')->delete();

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
