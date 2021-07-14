<?php

namespace App\Http\Controllers\Administracion;

use App\Facultad;
use App\Escuelas;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EscuelasController extends Controller
{
    public function getListarFacultades(Request $request){
        if(!$request->ajax()) return redirect('/');
        $facultades = Facultad::get()->all();
        return $facultades;
    }
    public function setRegistrarFacultad(Request $request){
        if(!$request->ajax()) return redirect('/');

        $cNombre = $request->cNombre;
        $cNombre = ($cNombre == NULL) ? ($cNombre = '') : $cNombre;

        Facultad::create([
            'nombre' => $cNombre
        ]);

    }
    public function getListarEscuelas(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdEscuela     =   $request->nIdEscuela;
        $cNombre        =   $request->cNombre;
        $nIdFacultad    =   $request->nIdFacultad;

        $nIdEscuela     = ($nIdEscuela == NULL)     ? ($nIdEscuela = '')   : $nIdEscuela;
        $cNombre        = ($cNombre == NULL)        ? ($cNombre = '')     : $cNombre;
        $nIdFacultad    = ($nIdFacultad == NULL)    ? ($nIdFacultad = '') : $nIdFacultad;

        $rpta = Escuelas::where('id', 'like', "%$nIdEscuela%")
                        ->where('nombre', 'like', "%$cNombre%")
                        ->where('id_facultad', 'like', "%$nIdFacultad%")
                        ->get();
        foreach($rpta as $escuela){
            $escuela->Facultad;
        }
        return $rpta;
    }
    public function setRegistrarEscuelas(Request $request){
        if(!$request->ajax()) return redirect('/');

        $cNombre     =   $request->cNombre;
        $nIdFacultad = $request->nIdFacultad;

        $cNombre     = ($cNombre == NULL)       ? ($cNombre = '')     : $cNombre;
        $nIdFacultad = ($nIdFacultad == NULL)   ? ($nIdFacultad = '') : $nIdFacultad;

        Escuelas::create([
            'nombre' => $cNombre,
            'id_facultad' => $nIdFacultad
        ]);
    }
    public function setEditarEscuelas(Request $request){

        if(!$request->ajax()) return redirect('/');

        $nIdEscuela     =  $request->nIdEscuela;
        $cNombre        =  $request->cNombre;
        $nIdFacultad    =  $request->nIdFacultad;

        $nIdEscuela     = ($nIdEscuela == NULL)  ? ($nIdEscuela = 0)    : $nIdEscuela;
        $cNombre        = ($cNombre == NULL)     ? ($cNombre = '')      : $cNombre;
        $nIdFacultad    = ($nIdFacultad == NULL) ? ($nIdFacultad = '1')  : $nIdFacultad;

        $escuela = Escuelas::find($nIdEscuela);
        $escuela->nombre = $cNombre;
        $escuela->id_facultad = $nIdFacultad;
        $escuela->save();

    }

}
