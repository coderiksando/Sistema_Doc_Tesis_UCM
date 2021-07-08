<?php

namespace App\Http\Controllers\Administracion;

use App\AreaTesis;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AreaTesisController extends Controller
{
    public function getListarAreaTesis(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdAreaTesis     = $request->nIdAreaTesis;
        $cNombre   =   $request->cNombre;
        $nIdEscuela      =   $request->nIdEscuela;
        $nIdAreaTesis = ($nIdAreaTesis == NULL) ? ($nIdAreaTesis = 0) : $nIdAreaTesis;
        $cNombre = ($cNombre == NULL) ? ($cNombre = '') : $cNombre;
        $nIdEscuela    = ($nIdEscuela == NULL) ? ($nIdEscuela = 0) : $nIdEscuela;

        $rpta = DB::select('call sp_AreaTesis_getListarAreaTesis (?, ?, ?)',
                                                                [
                                                                    $nIdAreaTesis,
                                                                    $cNombre,
                                                                    $nIdEscuela
                                                                ]);
        return $rpta;
    }
    public function setRegistrarAreaTesis(Request $request){

        if(!$request->ajax()) return redirect('/');

        $cNombre   =   $request->cNombre;
        $nIdEscuela      =   $request->nIdEscuela;
        $cNombre = ($cNombre == NULL) ? ($cNombre = '') : $cNombre;
        $nIdEscuela    = ($nIdEscuela == NULL) ? ($nIdEscuela = '') : $nIdEscuela;

        $testarea = DB::table('areatesis')->where('nombre', $cNombre)->where('id_escuela', $nIdEscuela)->exists();

        if ( $testarea){
            return response(['error' => 'Elemento ya existe'], 409);
        }
        else{
            DB::select('call sp_AreaTesis_setRegistrarAreaTesis (?, ?)',
                                                                [
                                                                    $cNombre,
                                                                    $nIdEscuela
                                                                ]);
        }
    }
    public function setEditarAreaTesis(Request $request){

        if(!$request->ajax()) return redirect('/');

        $testarea = DB::table('areatesis')->where('nombre', $request->nombre)->where('id_escuela', $request->id_escuela)->where('id', '<>', $request->id)->exists();

        if ($testarea){
            return response(['error' => 'Elemento ya existe'], 409);
        }
        else{
            $id =   $request->id;
            AreaTesis::find($id)->update($request->all());
        }


        /*
        $nIdAreaTesis =   $request->nIdAreaTesis;
        $cNombre    =   $request->cNombre;
        $nIdEscuela    =   $request->nIdEscuela;
        $nIdAreaTesis = ($nIdAreaTesis == NULL) ? ($nIdAreaTesis = 0) : $nIdAreaTesis;
        $cNombre = ($cNombre == NULL) ? ($cNombre = '') : $cNombre;
        $nIdEscuela = ($nIdEscuela == NULL) ? ($nIdEscuela = '') : $nIdEscuela;

        DB::select('call sp_AreaTesis_setEditarAreaTesis (?, ?, ?)',
                                                                [
                                                                    $nIdAreaTesis,
                                                                    $cNombre,
                                                                    $nIdEscuela
                                                                ]);
                                                                */
    }

}
