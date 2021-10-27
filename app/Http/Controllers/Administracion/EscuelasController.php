<?php

namespace App\Http\Controllers\Administracion;

use App\Facultad;
use App\Escuelas;
use App\DocumentosEscuela;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Debugbar;

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

        return $documentos->orderBy('id_escuela')->orderBy('created_at')->get();

    }

    public function getSeleccionarDocumentoEscuela(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdDoc = $request->nIdDoc;
        $nIdDoc = ($nIdDoc == NULL) ? ($nIdDoc = 0) : $nIdDoc;

        $doc = DocumentosEscuela::find($nIdDoc);
        return $doc;

    }

    public function setEditarDocumentoEscuela(Request $request){
        if(!$request->ajax()) return redirect('/');
        $rol = $request->session()->get('rol');

        $id             = $request->id;
        $descripcion    = $request->descripcion;
        $id_archivo     = $request->id_archivo;
        $file           = $request->file;
        $fecha          = Carbon::now();

        if(!$file){
            DocumentosEscuela::find($id)->update(['descripcion'=>$descripcion, 'updated_at' =>$fecha]);

        }else{
            $archivo = DocumentosEscuela::find($id);
            $name = last(explode('/', $archivo->path));
            Storage::delete('public/users/'.$name);

            $timestamp = Carbon::now()->format('H-i-s');
            $bandera = Str::random(5).$timestamp;
            $filename = $file->getClientOriginalName();
            $fileserver = $bandera .'_'. $filename;
            Storage::putFileAs('public/users', $file, $fileserver);

            DocumentosEscuela::find($id)->update(['descripcion'=>$descripcion, 'path' => asset('storage/users/'.$fileserver), 'updated_at' =>$fecha, 'id_archivo' =>$id_archivo]);

        }
        $this->reg('Editar Documento Escuela', $id, $rol);
    }
    public function setRegistrarLinkEscuela(Request $request){
        if(!$request->ajax()) return redirect('/');

        $user = Auth::user();
        $id_escuela  = $request->id_escuela;
        $descripcion = $request->descripcion;
        $path        = $request->path;

        if (stripos($path, 'http') === false) {
            $path = 'http://'.$path;
        }

        if ($id_escuela < 0) $id_escuela = $user->id_escuela;

        $rpta = new DocumentosEscuela;
        $rpta->descripcion = $descripcion;
        $rpta->path = $path;
        $rpta->id_escuela = $id_escuela;
        $rpta->link = 1;
        $rpta->save();
        return $rpta;
    }

    public function setEditarLinkEscuela(Request $request){
        if(!$request->ajax()) return redirect('/');

        $user = Auth::user();
        $id          = $request->id;
        $descripcion = $request->descripcion;
        $path        = $request->path;

        if (stripos($path, 'http') === false) {
            $path = 'http://'.$path;
        }

        DocumentosEscuela::find($id)->update(['descripcion'=>$descripcion, 'path' => $path]);
    }
    public function getEscuela(Request $request){
        if(!$request->ajax()) return redirect('/');
        $user = Auth::user();
        $escuela = $user->Escuelas;

        return $escuela;
    }
    public function getListarEscuelasByFITsByProfesor(Request $request) {
        if(!$request->ajax()) return redirect('/');
        $escuelas = DB  ::table('fit')
                        ->join('escuelas','escuelas.id','=','fit.id_escuela')
                        ->where(function ($fit) use ($request) {
                            $fit->where('fit.id_p_guia','=',"$request->nIdProfesor")
                                ->orWhere('fit.id_p_co_guia','=',"$request->nIdProfesor");
                        })
                        ->select('escuelas.id','escuelas.nombre')
                        ->groupBy('escuelas.id')
                        ->get();
        Debugbar::info($escuelas);
        $listEscuelas = [];
        foreach ($escuelas as $escuela) {
            array_push($listEscuelas, $escuela);
        }
        return $listEscuelas;
    }

}
