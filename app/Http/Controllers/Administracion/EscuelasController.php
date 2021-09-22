<?php

namespace App\Http\Controllers\Administracion;

use App\Facultad;
use App\Escuelas;
use App\DocumentosEscuela;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

        $rpta = Escuelas::where('nombre', 'like', "%$cNombre%");
        if ($nIdEscuela) $rpta = $rpta->where('id', '=', "$nIdEscuela");
        if ($nIdFacultad) $rpta = $rpta->where('id_facultad', '=', "$nIdFacultad");
        $rpta = $rpta->get();
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

        $testEscuela = DB::table('escuelas')->where('nombre', $cNombre)->where('id_facultad', $nIdFacultad)->exists();

        if ($testEscuela){
            return response(['error' => 'Elemento ya existe'], 409);
        }else{
            Escuelas::create([
                'nombre' => $cNombre,
                'id_facultad' => $nIdFacultad
            ]);
        }
    }
    public function setEditarEscuelas(Request $request){

        if(!$request->ajax()) return redirect('/');

        $nIdEscuela     =  $request->nIdEscuela;
        $cNombre        =  $request->cNombre;
        $nIdFacultad    =  $request->nIdFacultad;

        $nIdEscuela     = ($nIdEscuela == NULL)  ? ($nIdEscuela = 0)    : $nIdEscuela;
        $cNombre        = ($cNombre == NULL)     ? ($cNombre = '')      : $cNombre;
        $nIdFacultad    = ($nIdFacultad == NULL) ? ($nIdFacultad = '1')  : $nIdFacultad;

        $testEscuela = DB::table('escuelas')->where('nombre', $cNombre)->where('id_facultad', $nIdFacultad)->exists();

        if ($testEscuela){
            return response(['error' => 'Elemento ya existe'], 409);
        }else{
            $escuela = Escuelas::find($nIdEscuela);
            $escuela->nombre = $cNombre;
            $escuela->id_facultad = $nIdFacultad;
            $escuela->save();
        }
    }

    public function getListarDocumentosEscuela(Request $request){
        if(!$request->ajax()) return redirect('/');

        $user = Auth::user();
        $nIdEscuela =  $request->nIdEscuela;

        if ($nIdEscuela == -1) $nIdEscuela = $user->id_escuela;

        $documentos = DocumentosEscuela::where('id_escuela', $nIdEscuela);

        if ($request->nIdEscuela == -1) {
            $documentos->orWhere('id_escuela', 0);
        }

        return $documentos->get();

    }

}
