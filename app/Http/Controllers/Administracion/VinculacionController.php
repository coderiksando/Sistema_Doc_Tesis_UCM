<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VinculacionController extends Controller
{
    public function getListarVinculacion(Request $request){

        //if(!$request->ajax()) return redirect('/');

        $nIdVinculacion     =   $request->nIdVinculacion;
        $cNombre            =   $request->cNombre;
        $cTipo             =   $request->cTipo;
        $cDescripcion       =   $request->cDescripcion;

        $nIdVinculacion     = ($nIdVinculacion == NULL) ? ($nIdVinculacion = 0) : $nIdVinculacion;
        $cNombre            = ($cNombre == NULL) ? ($cNombre = '') : $cNombre;
        $cTipo             = ($cTipo == NULL) ? ($cTipo = '') : $cTipo;
        $cDescripcion       = ($cDescripcion == NULL) ? ($cDescripcion = '') : $cDescripcion;

        $rpta = DB::select('call sp_Vinculacion_getListarVinculacion (?, ?, ?, ?)',
                                                                [
                                                                    $nIdVinculacion,
                                                                    $cNombre,
                                                                    $cTipo,
                                                                    $cDescripcion
                                                                ]);
        $rpta = collect($rpta)->where('estado','A');
        // $rpta->where('estado', 'A')->get();
        return $rpta;
    }

    public function getListarVinculacionActiva(Request $request){

        if(!$request->ajax()) return redirect('/');

        $rpta = DB::table('vinculaciones')->where('estado', 'A')->get();

        //dd($rpta);
        return $rpta;
    }
    public function setRegistrarVinculacion(Request $request){

        if(!$request->ajax()) return redirect('/');

        $cNombre           =   $request->cNombre;
        $cTipo             =   $request->cTipo;
        $cDescripcion      =   $request->cDescripcion;

        $cNombre = ($cNombre == NULL) ? ($cNombre = '') : $cNombre;
        $cTipo    = ($cTipo == NULL) ? ($cTipo = '') : $cTipo;
        $cDescripcion    = ($cDescripcion == NULL) ? ($cDescripcion = '') : $cDescripcion;

        $testvinculacion = DB::table('vinculaciones')->where('nombre', $cNombre)->where('tipo', $cTipo)->exists();

        if ( $testvinculacion){
            return response(['error' => 'Elemento ya existe'], 409);
        }
        else{
            DB::select('call sp_Vinculacion_setRegistrarVinculacion (?, ?, ?)',
                                                                [
                                                                    $cNombre,
                                                                    $cTipo,
                                                                    $cDescripcion
                                                                ]);
        }
    }
    public function setEditarVinculacion(Request $request){

        if(!$request->ajax()) return redirect('/');

        $nIdVinculacion =   $request->nIdVinculacion;
        $cNombre    =   $request->cNombre;
        $cTipo      =   $request->cTipo;
        $cDescripcion      =   $request->cDescripcion;

        $nIdVinculacion = ($nIdVinculacion == NULL) ? ($nIdVinculacion = 0) : $nIdVinculacion;
        $cNombre = ($cNombre == NULL) ? ($cNombre = '') : $cNombre;
        $cTipo    = ($cTipo == NULL) ? ($cTipo = '') : $cTipo;
        $cDescripcion    = ($cDescripcion == NULL) ? ($cDescripcion = '') : $cDescripcion;

        $testvinculacion = DB::table('vinculaciones')->where('nombre', $cNombre)->where('tipo', $cTipo)->exists();

        if ( $testvinculacion){
            return response(['error' => 'Elemento ya existe'], 409);
        }
        else{
            DB::select('call sp_Vinculacion_setEditarVinculacion (?, ?, ?, ?)',
                                                                [
                                                                    $nIdVinculacion,
                                                                    $cNombre,
                                                                    $cTipo,
                                                                    $cDescripcion
                                                                ]);
        }
    }
    public function setCambiarVinculacion(Request $request){

        if(!$request->ajax()) return redirect('/');

        $nIdVinculacion   = $request->nIdVinculacion;
        $cEstadopg  = $request->cEstadopg;

        $nIdVinculacion   = ($nIdVinculacion == NULL) ? ($nIdVinculacion = 0) : $nIdVinculacion;
        $cEstadopg  = ($cEstadopg == NULL) ? ($cEstadopg = 0) : $cEstadopg;

        $rpta = DB::select('call sp_Vinculacion_setCambiarVinculacion (?, ?)',
                                                                [
                                                                    $nIdVinculacion,
                                                                    $cEstadopg
                                                                ]);
        }
}
