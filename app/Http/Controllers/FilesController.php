<?php

namespace App\Http\Controllers;

use App\ArchivoPdf;
use App\Fit_User;
use App\Fit;
use App\User;
use App\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \stdClass;
use Debugbar;

class FilesController extends Controller
{
    public function setRegistrarArchivo(Request $request){
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

        $idUser = Auth::user()->id_user;
        $fit = Fit_User::Firstwhere('id_user', $idUser)->Fit;
        $file = $request->file;
        $bandera = Str::random(10);
        $filename = $file->getClientOriginalName();
        $fileserver = $bandera .'_'. $filename;
        $tipo = $request->tipo;

        Storage::putFileAs('public/users', $file, $fileserver);

        if ($tipo == 'final_t' || $tipo == 'constancia_t'){
            $lastVersion = $this->getPdfFinal($request);
            if($lastVersion){
                $name = last(explode('/', $lastVersion->path));
                Storage::delete('public/users/'.$name);
                $lastVersion->delete();
            }
        }

        $rpta = new ArchivoPdf;
        $rpta->path = asset('storage/users/'.$fileserver);
        $rpta->filename = $filename;
        $rpta->id_fit = $fit->id;
        $rpta->tipo_pdf = $tipo;
        $rpta->save();
        //$nIdFile = $rpta->id;
        return $rpta;
    }

    public function getPdfFinal(Request $request){
        $tipo = $request->tipo;
        $idUser = Auth::user()->id_user;
        $fit = Fit_User::Firstwhere('id_user', $idUser)->Fit;
        $archivoPDF = ArchivoPdf::where('id_fit', $fit->id)->orderByDesc('created_at')->firstWhere('tipo_pdf', $tipo);
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
                $rpta->tipo_pdf = 'final_t';
                $rpta->save();
            }
        } else {
            $registroFit = new Fit;
            $registroFit->id_p_guia = $request->nIdPg;
            $registroFit->id_p_co_guia = $request->nIdCoPg;
            $registroFit->id_vinculacion = $request->nIdVinculacion;
            $registroFit->titulo = $request->cTitulo;
            $registroFit->tipo = $request->cTipo;
            $registroFit->objetivo_general = $request->cObjetivoGeneral;
            $registroFit->objetivo_especifico = $request->cObjetivoEspecifico;
            $registroFit->descripcion = $request->cDescripcion;
            $registroFit->contribucion = $request->cContribucion;
            $registroFit->estado = 'A';
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
                    $newUser->save();
                    $newFitUser = new Fit_User;
                    $newFitUser->id_user = $newUser->id_user;
                    $newFitUser->id_fit = $registroFit->id;
                    $newFitUser->save();
                }
            }
            return $registroFit->id;
        }
        return response()->json(['ok' => 'ok'], 200);
    }
}
