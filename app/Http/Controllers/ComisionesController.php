<?php

namespace App\Http\Controllers;

use App\Comisiones;
use App\ArchivoPdf;
use App\User;
use App\Fit;
use App\Revision_Comision;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Debugbar;

class ComisionesController extends Controller
{
    public function setRegistrarComision(Request $request){

        if(!$request->ajax()) return redirect('/');

        $idTesis = $request->idTesis;
        $tesis = Fit::find($idTesis);

        if($tesis->Comisiones)
        {
            return response(['error' => 'Comisión ya existe'], 409);
        }
        else{
            $comision = new Comisiones();
            $comision->id_tesis = $idTesis;
            $comision->id_profesor1 = $request->Profesor1;
            $comision->id_profesor2 = $request->Profesor2;
            $comision->p_externo = $request->NombrePEx;
            $comision->correo_p_externo = $request->EmailPEx;
            $comision->institucion_p_externo = $request->InstitucionPEx;
            $comision->save();
            $this->reg('Registrar Comisión', $comision->id,  $request->session()->get('rol'));

            return $comision;
        }
    }

    public function getComision(Request $request){
        if(!$request->ajax()) return redirect('/');
        $IdComision = $request->id;
        $Comision = Comisiones::find($IdComision);
        $Comision->UserP1;
        $Comision->UserP2;
        $Comision->Fit->Fit_User;
        foreach ($Comision->Fit->Fit_User as $fit_user) {
            $fit_user->User;
        }
        return $Comision;
    }
    public function setEditarComision(Request $request){
        if(!$request->ajax()) return redirect('/');

        $id                     = $request->IdComision;
        $comision   = Comisiones::find($id);

        $comision->id_profesor1             = $request->Profesor1;
        $comision->id_profesor2             = $request->Profesor2;
        $comision->p_externo                = $request->NombrePEx;
        $comision->correo_p_externo         = $request->EmailPEx;
        $comision->institucion_p_externo    = $request->InstitucionPEx;
        $comision->update();
        $this->reg('Editar comisión', 'Profesor');

        $this->reg('Editar Comisión', $id,  $request->session()->get('rol'));

        return;
    }
    public function getListarMisComisiones(Request $request){
        if(!$request->ajax()) return redirect('/');
        $IdProfesor  = Auth::id();
        $MisComisiones = Fit::where('id_p_guia', $IdProfesor)
                            ->get()
                            ->sortByDesc('updated_at')
                            ->values()
                            ->all();
        if ($MisComisiones) {
            foreach ($MisComisiones as $fit) {
                if ($fit->Comisiones) {
                    $fit->Comisiones->UserP1;
                    $fit->Comisiones->UserP2;
                }
                $fit->User_P_Guia;
                $fit->User_P_Coguia;
                $fit->Fit_User;
                if ($fit->Fit_User) {
                    foreach ($fit->Fit_User as $user_comision) {
                        $user_comision->User;
                    }
                }
            }
        }
        return $MisComisiones;
    }
    public function getListarComisiones(Request $request){
        if(!$request->ajax()) return redirect('/');
        $IdProfesor  = Auth::id();
        $ComisionesTotales = [];
        $Comisiones  = Comisiones   ::where('id_profesor1',$IdProfesor)
                                    ->get()
                                    ->sortByDesc('updated_at')
                                    ->all();
        if ($Comisiones) {
            foreach ($Comisiones as $comision) {
                $comision->Fit;
                $comision->Fit->User_P_Guia;
                $comision->Fit->ArchivoPdf;
                $comision->UserP1;
                $comision->UserP2;
                $comision->Fit->Fit_User;
                if ($comision->Fit->Fit_User) {
                    foreach ($comision->Fit->Fit_User as $comision_fit_user) {
                        $comision_fit_user->User;
                    }
                }
                array_push($ComisionesTotales, $comision);
            }
        }
        $Comisiones  = Comisiones   ::where('id_profesor2',$IdProfesor)
                                    ->get()
                                    ->sortByDesc('updated_at')
                                    ->all();
        if ($Comisiones) {
            foreach ($Comisiones as $comision) {
                $comision->Fit;
                $comision->Fit->User_P_Guia;
                $comision->Fit->ArchivoPdf;
                $comision->UserP1;
                $comision->UserP2;
                $comision->Fit->Fit_User;
                if ($comision->Fit->Fit_User) {
                    foreach ($comision->Fit->Fit_User as $comision_fit_user) {
                        $comision_fit_user->User;
                    }
                }
                array_push($ComisionesTotales, $comision);
            }
        }
        return $ComisionesTotales;
    }
    public function pathDocumentoComision(Request $request){
        if(!$request->ajax()) return redirect('/');
        Debugbar::info($request->id);
        $archivoPDF = ArchivoPdf::where('id_fit', $request->id)
                                ->where(function ($tipo) {
                                    $tipo   ->where('tipo_pdf', '=', 'final_t')
                                            ->orWhere('tipo_pdf', '=', 'avance_t');
                                })
                                ->orderBy('updated_at', 'desc')
                                ->first();
        return $archivoPDF;
    }
    public function setRegistrarDocumentoComision(Request $request){
        if(!$request->ajax()) return redirect('/');
        if ($request->file) {
            // consultar si existen de estos archivos antes
            $oldDocumentoComision = Revision_Comision   ::where('id_fit', $request->id_fit)
                                                        ->where('id_user', Auth::id())
                                                        ->first();
            if ($oldDocumentoComision) {
                $oldArchivoPDF = ArchivoPdf::find($oldDocumentoComision->id_archivo);
                Debugbar::info($oldDocumentoComision,$oldArchivoPDF);
                $oldDocumentoComision->delete();
                if ($oldArchivoPDF) {
                    $oldFileName = last(explode('/', $oldArchivoPDF->path));
                    Storage::delete('public/users/'.$oldFileName);
                    $oldArchivoPDF->delete();
                }
            }
            $file = $request->file;
            $bandera = Str::random(10);
            $filename = $file->getClientOriginalName();
            $fileserver = $bandera .'_'. $filename;
            $guardado = Storage::putFileAs('public/users', $file, $fileserver);
            if ($guardado) {
                $archivoPDF = new ArchivoPdf;
                $archivoPDF->path = asset('storage/users/'.$fileserver);
                $archivoPDF->filename = $filename;
                $archivoPDF->id_fit = $request->id_fit;
                $archivoPDF->tipo_pdf = 'general';
                $archivoPDF->save();
                $revComision = new Revision_Comision;
                $revComision->id_fit     = $request->id_fit;
                $revComision->id_user    = Auth::id();
                $revComision->id_archivo = $archivoPDF->id;
                $revComision->tipo       = $request->tipo;
                $revComision->comentario = $request->comentario;
                $revComision->save();
            }
        }
    }

}
