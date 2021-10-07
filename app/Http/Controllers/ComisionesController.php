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
use Carbon\Carbon;
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
            if ( $request->estado ) $comision->estado = $request->estado;
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
        $comision->estado                   = $request->estado;
        $comision->update();
        $this->reg('Editar comisión', 'Profesor');

        $this->reg('Editar Comisión', $id,  $request->session()->get('rol'));

        return;
    }
    public function getListarMisComisiones(Request $request){
        //if(!$request->ajax()) return redirect('/');
        $IdProfesor = Auth::id();
        $nomAlumno  = $request->cAlum;
        $startDate  = Carbon::parse($request->startDate)->startOfDay();
        $endDate    = Carbon::parse($request->endDate)->endOfDay();
        $tituloFid  = $request->cTitulo;
        $bComision  = $request->bComision;
        $fitBusq =   DB ::table('fit')
                        ->leftJoin('fit_user', 'fit_user.id_fit', 'fit.id')
                        ->leftJoin('users as alumno', 'alumno.id_user', 'fit_user.id_user')
                        ->leftJoin('users as profesor', 'profesor.id_user', 'fit.id_p_guia');
        if ($bComision == '0') $fitBusq->leftJoin('comisiones', 'comisiones.id_tesis', 'fit.id')->whereNull('comisiones.id_tesis');
        if ($bComision == '1') $fitBusq->join('comisiones', 'comisiones.id_tesis', 'fit.id');
        if ($bComision == '2') $fitBusq->leftJoin('comisiones', 'comisiones.id_tesis', 'fit.id');
        $fitBusq = $fitBusq ->select('fit.id')
                            ->where(DB::raw("CONCAT(alumno.nombres,' ',alumno.apellidos)"), 'like', "%$nomAlumno%")
                            ->where('fit.id_p_guia', "$IdProfesor");
        if ($request->startDate && $request->endDate) $fitBusq = $fitBusq->whereBetween('fit.updated_at', [$startDate,$endDate]);
        $fitBusq = $fitBusq ->where('fit.titulo', 'like', "%$tituloFid%")
                            ->get()
                            ->pluck('id');
        $MisComisiones = Fit::whereIn('id', $fitBusq)
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
                $fit->Escuela;
                $fit->Fit_User;
                if ($fit->Fit_User) {
                    foreach ($fit->Fit_User as $user_comision) {
                        $user_comision->User;
                    }
                }
                $fit->archivoActa = $fit->ArchivoPdf->where('tipo_pdf','acta')->values();
                $fit->archivoPendienteRevision = $fit->ArchivoPdf->where('tipo_pdf','revision')->first();

            }
        }
        return $MisComisiones;
    }
    public function getListarComisiones(Request $request){
        if(!$request->ajax()) return redirect('/');
        $IdProfesor = Auth::id();
        $nomAlumno  = $request->cAlum;
        $startDate  = Carbon::parse($request->startDate)->startOfDay();
        $endDate    = Carbon::parse($request->endDate)->endOfDay();
        $tituloFid  = $request->cTitulo;
        $fitBusq    = DB::table('fit')
                        ->leftJoin('fit_user', 'fit_user.id_fit', 'fit.id')
                        ->leftJoin('users as alumno', 'alumno.id_user', 'fit_user.id_user')
                        ->leftJoin('users as profesor', 'profesor.id_user', 'fit.id_p_guia')
                        ->join('comisiones', 'comisiones.id_tesis', 'fit.id')
                        ->select('fit.id')
                        ->where(DB::raw("CONCAT(alumno.nombres,' ',alumno.apellidos)"), 'like', "%$nomAlumno%")
                        ->where(function ($revisor) use ($IdProfesor) {
                            $revisor->where('comisiones.id_profesor1', '=', "$IdProfesor")
                                    ->orWhere('comisiones.id_profesor2', '=', "$IdProfesor");
                        });
        if ($request->estado) $fitBusq = $fitBusq->where('comisiones.estado',"$request->estado");
        if ($request->startDate && $request->endDate) $fitBusq = $fitBusq->whereBetween('fit.updated_at', [$startDate,$endDate]);
        $fitBusq =  $fitBusq->where('fit.titulo', 'like', "%$tituloFid%")
                            ->get()
                            ->pluck('id');
        $comisiones = [];
        if (count($fitBusq) > 0) {
            $comisiones = Comisiones::whereIn('id_tesis',$fitBusq)
                                    ->get()
                                    ->sortByDesc('updated_at')
                                    ->values()
                                    ->all();
        }
        if (count($comisiones) > 0) {
            foreach ($comisiones as $comision) {
                $comision->Fit;
                $comision->Fit->User_P_Guia;
                $comision->Fit->ArchivoPdf;
                $comision->Fit->Escuela;
                $comision->UserP1;
                $comision->UserP2;
                $comision->Fit->Fit_User;
                if ($comision->Fit->Fit_User) {
                    foreach ($comision->Fit->Fit_User as $comision_fit_user) {
                        $comision_fit_user->User;
                    }
                }
                $comision->Fit->archivoActa = $comision->Fit->ArchivoPdf->where('tipo_pdf','acta')->values();
                $comision->revisionComisionPropia = $comision->Fit->Revision_Comision->where('id_user', "$IdProfesor")->where('tipo','revision');
                $comision->archivoPendienteRevision = $comision->Fit->ArchivoPdf->where('tipo_pdf','revision')->first();
            }
        }
        return $comisiones;
    }
    public function getListarTodasComisiones(Request $request){
        if(!$request->ajax()) return redirect('/');
        Debugbar::info(Auth::user()->id_escuela);
        $comisiones = Comisiones::get()->pluck('id_tesis');
        $fitWithComision = Fit  ::whereIn('id', $comisiones)
                                ->where('id_escuela', Auth::user()->id_escuela)
                                ->get()
                                ->all();
        foreach ($fitWithComision as $fit) {
            $fit->Comisiones;
            $fit->Comisiones->UserP1;
            $fit->Comisiones->UserP2;
            $fit->User_P_Guia;
            $fit->Escuela;
            $fit->getAlumnos();
            $fit->Revision_Comision;
            $fit->ArchivoPdf;
            $fit->archivoActa = $fit->ArchivoPdf->where('tipo_pdf','acta')->values();
            $fit->archivoPendienteRevision = $fit->ArchivoPdf->where('tipo_pdf','revision')->first();
        }
        return $fitWithComision;
    }
    public function getListarComisionesByParametros(Request $request){
        if(!$request->ajax()) return redirect('/');
        $nomAlumno  = $request->cAlum;
        $nomProfe   = $request->cProf;
        $startDate  = Carbon::parse($request->startDate)->startOfDay();
        $endDate    = Carbon::parse($request->endDate)->endOfDay();
        $tituloFid  = $request->cTitulo;
        $bComision  = $request->bComision;
        $fitBusq =   DB  ::table('fit')
                        ->leftJoin('fit_user', 'fit_user.id_fit', 'fit.id')
                        ->leftJoin('users as alumno', 'alumno.id_user', 'fit_user.id_user')
                        ->leftJoin('users as profesor', 'profesor.id_user', 'fit.id_p_guia');
        if ($bComision == '0') $fitBusq->leftJoin('comisiones', 'comisiones.id_tesis', 'fit.id')->whereNull('comisiones.id_tesis');
        if ($bComision == '1') $fitBusq->join('comisiones', 'comisiones.id_tesis', 'fit.id');
        if ($bComision == '2') $fitBusq->leftJoin('comisiones', 'comisiones.id_tesis', 'fit.id');
        $fitBusq = $fitBusq ->select('fit.id')
                            ->where(DB::raw("CONCAT(alumno.nombres,' ',alumno.apellidos)"), 'like', "%$nomAlumno%")
                            ->where(DB::raw("CONCAT(profesor.nombres,' ',profesor.apellidos)"), 'like', "%$nomProfe%");
        if ($request->startDate && $request->endDate) $fitBusq = $fitBusq->whereBetween('fit.updated_at', [$startDate,$endDate]);
        $fitBusq = $fitBusq ->where('fit.titulo', 'like', "%$tituloFid%")
                            ->get()
                            ->pluck('id');

        $fitWithComision= Fit::whereIn('id', $fitBusq)
                            ->get()
                            ->all();
        foreach ($fitWithComision as $fit) {
            $fit->Comisiones;
            if ($fit->Comisiones) {
                $fit->Comisiones->UserP1;
                $fit->Comisiones->UserP2;
            }
            $fit->User_P_Guia;
            $fit->Escuela;
            $fit->getAlumnos();
            $fit->Revision_Comision;
            $fit->ArchivoPdf;
        }
        return $fitWithComision;
    }
    public function pathDocumentoComision(Request $request){
        if(!$request->ajax()) return redirect('/');
        Debugbar::info($request->id);
        $documentoRevision = ArchivoPdf ::where('id_fit', $request->id)
                                        ->where('tipo_pdf', 'revision')
                                        ->get()->first();
        return $documentoRevision;
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
    public function borrarComision(Request $request){
        if(!$request->ajax()) return redirect('/');
        $fit = Fit  ::where('id',"$request->id")
                    ->where('aprobado_pg', 'EA')
                    ->get()
                    ->first();
        if ($fit) {
            $comision = Comisiones::where('id_tesis', "$request->id")->delete();
            $archivosComision = ArchivoPdf  ::where('id_fit', "$request->id")
                                            ->where('tipo_pdf', 'revision')
                                            ->update(['tipo_pdf' => 'avance_t']);
            return response(['msg' => 'Comisión y archivos de revisión eliminados'], 200);
        }
        else return response(['msg' => 'Comisión no encontrada'], 200);
    }
}
