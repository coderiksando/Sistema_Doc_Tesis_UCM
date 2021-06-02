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
            if (count($parametros) > 1) {
                array_push($arrayResultado, $parametros);
            }else {
                array_push($arrayResultado, $parametros[0]);
            }
        }

        return $arrayResultado;
    }
}
