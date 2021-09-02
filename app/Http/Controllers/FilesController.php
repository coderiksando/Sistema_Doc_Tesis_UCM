<?php

namespace App\Http\Controllers;

use App\ArchivoPdf;
use App\DocumentosEscuela;
use App\Fit_User;
use App\Fit;
use App\User;
use App\Users_Roles;
use App\File;
use App\Comisiones;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \stdClass;
use Debugbar;
use Carbon\Carbon;

class FilesController extends Controller
{
    public function setRegistrarArchivo(Request $request){
        if(!$request->ajax()) return redirect('/');

        $fileOwner = User::find(Auth::user()->id_user);
        // eliminacion del archivo anterior
        if ($fileOwner->File) {
            $oldFile = $fileOwner->File;
            $oldFileId = $fileOwner->File->id;
            $fileOwner->id_files = NULL;
            $fileOwner->save();
            $oldFileName = last(explode('/', $oldFile->path));
            Storage::delete('public/users/'.$oldFileName);
            $oldFile = File::find($oldFileId);
            $oldFile->delete();
        }
        $file = $request->file;
        $bandera = Str::random(10);
        $filename = $file->getClientOriginalName();
        $fileserver = $bandera .'_'. $filename;
        Storage::putFileAs('public/users', $file, $fileserver);
        $archivo = new File;
        $archivo->path = asset('storage/users/'.$fileserver);
        $archivo->filename = $filename;
        $archivo->save();
        return $archivo;
    }
    public function setRegistrarArchivoPDF(Request $request){
        if(!$request->ajax()) return redirect('/');

        $idUser = Auth::user()->id_user;
        $fit = Fit_User::Firstwhere('id_user', $idUser)->Fit;
        $file = $request->file;
        $bandera = Str::random(10);
        $tipo = $request->tipo;
        $rpta = new ArchivoPdf;

        if ($file) {
            $filename = $file->getClientOriginalName();
            $fileserver = $bandera .'_'. $filename;
            Storage::putFileAs('public/users', $file, $fileserver);

            if ($tipo == 'final_t' || $tipo == 'constancia_t' || $tipo == 'acta'){
                $lastVersion = $this->getArchivo($request);
                if($lastVersion){
                    $name = last(explode('/', $lastVersion->path));
                    Storage::delete('public/users/'.$name);
                    $lastVersion->delete();
                }
            }

            $rpta->path = asset('storage/users/'.$fileserver);
            $rpta->filename = $filename;
            $rpta->id_fit = $fit->id;
            $rpta->tipo_pdf = $tipo;
            $rpta->save();
        }

        if ($tipo == 'final_t'){
            $motivo = $request->motivo;
            $meses = $request->meses;
            $opcion = $request->formOption;
            if ($opcion == 2) {
                $fit->privado = 1;
                $fit->motivo_privado = $motivo;
            }else{
                $fit->privado = 0;
                $fit->motivo_privado = '';
                if ($opcion == 1) {
                    $fit->fecha_publicacion = Carbon::now()->addMonths($meses);
                }
            }
        $fit->save();
        }

        return $rpta;
    }

    public function setRegistrarArchivoEscuela(Request $request){
        if(!$request->ajax()) return redirect('/');

        $user = Auth::user();
        $file = $request->file;
        $timestamp = Carbon::now()->format('H-i-s');
        $bandera = Str::random(5).$timestamp;
        $filename = $file->getClientOriginalName();
        $fileserver = $bandera .'_'. $filename;
        $id_escuela = $request->id_escuela;
        $descripcion = $request->descripcion;

        if ($id_escuela < 0) $id_escuela = $user->id_escuela;

        Debugbar::info($timestamp);
        Storage::putFileAs('public/users', $file, $fileserver);

        $rpta = new DocumentosEscuela;
        $rpta->descripcion = $descripcion;
        $rpta->path = asset('storage/users/'.$fileserver);
        $rpta->filename = $filename;
        $rpta->id_escuela = $id_escuela;
        $rpta->save();
        return $rpta;
    }
    public function setEliminarDocumentoEscuela(Request $request){
        if(!$request->ajax()) return redirect('/');

        $id = $request->id;
        $archivo = DocumentosEscuela::find($id);

        $name = last(explode('/', $archivo->path));
        Storage::delete('public/users/'.$name);
        $archivo->delete();
    }

    public function getArchivo(Request $request){
        $tipo = $request->tipo;
        $fit = $request->fit;
        if (!$fit) {
            $idUser = Auth::user()->id_user;
            $fit = Fit_User::Firstwhere('id_user', $idUser)->Fit->id;
        }
        $archivoPDF = ArchivoPdf::where('id_fit', $fit)->orderByDesc('created_at')->firstWhere('tipo_pdf', $tipo);
        // Debugbar::info(last(explode('/', $archivoPDF->path)));
        return $archivoPDF;
    }
    public function setRegistrarTesisfinalizada(Request $request){
        if(!$request->ajax()) return redirect('/');
        if ($request->file) {
            $file = $request->file;
            $bandera = Str::random(10);
            $filename = $file->getClientOriginalName();
            $fileserver = $bandera .'_'. $filename;
            $guardado = Storage::putFileAs('public/users', $file, $fileserver);
            if ($guardado) {
                $rpta = new ArchivoPdf;
                $rpta->path = asset('storage/users/'.$fileserver);
                $rpta->filename = $filename;
                $rpta->id_fit = $request->id_fit;
                $rpta->tipo_pdf = $request->type;
                $rpta->save();
            }
        } else {
            $registroFit = new Fit;
            $registroFit->id_p_guia = $request->nIdPg;
            $registroFit->id_p_co_guia = $request->nIdCoPg;
            $registroFit->id_vinculacion = $request->nIdVinculacion;
            $registroFit->id_escuela = $request->nIdEscuela;
            $registroFit->titulo = $request->cTitulo;
            $registroFit->tipo = $request->cTipo;
            $registroFit->objetivo_general = $request->cObjetivoGeneral;
            $registroFit->objetivo_especifico = $request->cObjetivoEspecifico;
            $registroFit->descripcion = $request->cDescripcion;
            $registroFit->contribucion = $request->cContribucion;
            $registroFit->fecha = ($request->dFecha) ? $request->dFecha : Carbon::now();
            $registroFit->privado = $request->privado;
            if ($request->fidFinalizada) {
                $registroFit->nota = $request->Nota;
                if ($registroFit->nota >= 4) {
                    $registroFit->estado = 'A';
                } else {
                    $registroFit->estado = 'R';
                }
            } else {
                $registroFit->estado = 'D';
            }
            $registroFit->aprobado_pg = 'V';
            $registroFit->save();
            foreach ($request->cUsers as $user) {
                $user = (object) $user;
                if (property_exists($user, 'id_user')) {
                    $newFitUser = new Fit_User;
                    $newFitUser->id_user = $user->id_user;
                    $newFitUser->id_fit = $registroFit->id;
                    $newFitUser->save();
                } else {
                    $newUser = new User;
                    $newUser->rut = $user->rut;
                    $newUser->email = $user->email;
                    $newUser->nombres = $user->nombres;
                    $newUser->apellidos = $user->apellidos;
                    $newUser->password = Hash::make('12345');
                    $newUser->id_escuela = $registroFit->id_escuela;
                    $newUser->save();
                    $newFitUser = new Fit_User;
                    $newFitUser->id_user = $newUser->id_user;
                    $newFitUser->id_fit = $registroFit->id;
                    $newFitUser->save();
                    $newUserRol = new Users_Roles;
                    // se establece el usuario como alumno (2)
                    $newUserRol->id_user = $newUser->id_user;
                    $newUserRol->id_roles = 2;
                    $newUserRol->save();
                }
            }
            if (count($request->aComision) > 0 || $request->oProfExterno['fullname']) {
                $comision = new Comisiones;
                $comision->id_tesis = $registroFit->id;
                if (array_key_exists(0, $request->aComision))
                    $comision->id_profesor1 = $request->aComision[0]['id_user'];
                if (array_key_exists(1, $request->aComision))
                    $comision->id_profesor2 = $request->aComision[1]['id_user'];
                $comision->p_externo = $request->oProfExterno['fullname'];
                $comision->correo_p_externo = $request->oProfExterno['correo'];
                $comision->institucion_p_externo = $request->oProfExterno['institucion'];
                $comision->save();
            }
            return $registroFit->id;
        }
        return response()->json(['ok' => 'ok'], 200);
    }
    public function setEditarTesisfinalizada(Request $request){
        if(!$request->ajax()) return redirect('/');
        if ($request->file) {
            $archivoFit = ArchivoPdf::where('id_fit', $request->id_fit)
                                    ->where('tipo_pdf', $request->type)
                                    ->first();
            if ($archivoFit) {
                $oldFileName = last(explode('/', $archivoFit->path));
                Storage::delete('public/users/'.$oldFileName);
                $archivoFit->delete();
            }

            $file = $request->file;
            $bandera = Str::random(10);
            $filename = $file->getClientOriginalName();
            $fileserver = $bandera .'_'. $filename;
            $guardado = Storage::putFileAs('public/users', $file, $fileserver);
            if ($guardado) {
                $rpta = new ArchivoPdf;
                $rpta->path = asset('storage/users/'.$fileserver);
                $rpta->filename = $filename;
                $rpta->id_fit = $request->id_fit;
                $rpta->tipo_pdf = $request->type;
                $rpta->save();
            }
        } else {
            $registroFit = Fit::find($request->nIdTesis);
            $registroFit->id_p_guia = $request->nIdPg;
            $registroFit->id_p_co_guia = $request->nIdCoPg;
            $registroFit->id_vinculacion = $request->nIdVinculacion;
            $registroFit->id_escuela = $request->nIdEscuela;
            $registroFit->titulo = $request->cTitulo;
            $registroFit->tipo = $request->cTipo;
            $registroFit->objetivo_general = $request->cObjetivoGeneral;
            $registroFit->objetivo_especifico = $request->cObjetivoEspecifico;
            $registroFit->descripcion = $request->cDescripcion;
            $registroFit->contribucion = $request->cContribucion;
            if ($request->dFecha) {
                $registroFit->fecha = $request->dFecha;
            }
            $registroFit->privado = $request->privado;
            if ($request->fidFinalizada) {
                $registroFit->nota = $request->Nota;
                if ($registroFit->nota >= 4) {
                    $registroFit->estado = 'A';
                } else {
                    $registroFit->estado = 'R';
                }
            } else {
                $registroFit->estado = 'D';
            }
            $registroFit->aprobado_pg = 'V';
            $registroFit->save();
            // zona de insercion de usuarios
            $rows_fit_user = Fit_User::where('id_fit', $registroFit->id)->delete();
            foreach ($request->cUsers as $user) {
                $user = (object) $user;
                if (property_exists($user, 'id_user')) {
                    $newFitUser = new Fit_User;
                    $newFitUser->id_user = $user->id_user;
                    $newFitUser->id_fit = $registroFit->id;
                    $newFitUser->save();
                } else {
                    $newUser = new User;
                    $newUser->rut = $user->rut;
                    $newUser->email = $user->email;
                    $newUser->nombres = $user->nombres;
                    $newUser->apellidos = $user->apellidos;
                    $newUser->password = Hash::make('12345');
                    $newUser->id_escuela = $registroFit->id_escuela;
                    $newUser->save();
                    $newFitUser = new Fit_User;
                    $newFitUser->id_user = $newUser->id_user;
                    $newFitUser->id_fit = $registroFit->id;
                    $newFitUser->save();
                    $newUserRol = new Users_Roles;
                    // se establece el usuario como alumno (2)
                    $newUserRol->id_user = $newUser->id_user;
                    $newUserRol->id_roles = 2;
                    $newUserRol->save();
                }
            }
            // zona de insercion de comision
            $oldRegComision = Comisiones::where('id_tesis', $registroFit->id)->first();
            if ($oldRegComision) $oldRegComision->delete();
            $regComision = new Comisiones;
            $regComision->id_tesis = $registroFit->id;
            if ($request->aComision) {
                $contComision = 0;
                foreach ($request->aComision as $comision) {
                    $contComision++;
                    if ($contComision = 1) $regComision->id_profesor1 = $comision['id_user'];
                    if ($contComision = 2) $regComision->id_profesor2 = $comision['id_user'];
                }
            }
            if (array_key_exists('p_externo', $request->oProfExterno)) {
                $regComision->p_externo             =   $request->oProfExterno['p_externo'];
            }
            if (array_key_exists('correo_p_externo', $request->oProfExterno)) {
                $regComision->correo_p_externo      =   $request->oProfExterno['correo_p_externo'];
            }
            if (array_key_exists('institucion_p_externo', $request->oProfExterno)) {
                $regComision->institucion_p_externo =   $request->oProfExterno['institucion_p_externo'];
            }
            $regComision->save();
            return $registroFit->id;
        }
        return response()->json(['ok' => 'ok'], 200);
    }
}
