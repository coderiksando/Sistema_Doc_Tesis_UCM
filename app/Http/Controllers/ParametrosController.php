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
        $avancesTesisFormato = $request->AvancesTesisFormato;
        $actaFormato         = $request->ActaFormato;

        Parametro::where('parametro', 'MaxStudentNumber')->update(['valor' => $maxStudentNumber]);
        Parametro::where('parametro', 'AvancesTesisSize')->update(['valor' => $avancesTesisSize]);
        Parametro::where('parametro', 'ActaSize')->update(['valor' => $actaSize]);

        Parametro::where('parametro', 'AvancesTesisFormato')->delete();
        Parametro::where('parametro', 'ActaFormato')->delete();

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
        return 200;
    }
}
