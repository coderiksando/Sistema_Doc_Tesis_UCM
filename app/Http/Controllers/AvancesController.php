<?php

namespace App\Http\Controllers;

use App\AvancesTesis;
use App\Fit;
use App\Fit_User;
use App\User;
use App\ArchivoPdf;
use App\Parametro;
use \stdClass;
use App\Mail\MailAvances;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Debugbar;

class AvancesController extends Controller
{
    public function getListarAvances(Request $request){
        if(!$request->ajax()) return redirect('/');

        $idUser    = Auth::id();
        $nIdAvance = $request->nIdAvance;

        $idUser    = ($idUser == NULL) ? ($idUser = 0) : $idUser;
        $nIdAvance = ($nIdAvance == NULL) ? ($nIdAvance = 0) : $nIdAvance;
        $Fit = $this->getFit();
        $AvancesTesis = [];
        if($Fit){
            $AvancesTesis = $Fit->AvancesTesis->sortByDesc('updated_at')->values()->all();
            foreach($AvancesTesis as $avance){
                $avance->ArchivoPdf;
            }
        }
        return $AvancesTesis;
    }
    public function getListarAvancesByFit(Request $request){
        if(!$request->ajax()) return redirect('/');

        $Fit = Fit::find($request->Fit);
        $AvancesTesis = $Fit->AvancesTesis->sortByDesc('updated_at')->values()->all();
        foreach($AvancesTesis as $avance){
            $avance->ArchivoPdf;
        }

        return [$AvancesTesis, $Fit->Comisiones];
    }
    public function getListarAlumnosByprofesor(Request $request){
        if(!$request->ajax()) return redirect('/');

        $idUser     = Auth::id();
        $rol = $request->session()->get('rol');
        $nIdAvance  = $request->nIdAvance;
        $estado = $request->estado;

        $idUser     = ($idUser == NULL) ? ($idUser = 0) : $idUser;
        $nIdAvance  = ($nIdAvance == NULL) ? ($nIdAvance = 0) : $nIdAvance;
        $estado     = ($estado == NULL) ? ($estado = 'D') : $estado;
        $Users = [];

        if ($rol == 'Profesor') {
            $fits = Fit::where('id_p_guia', $idUser)->where('estado', $estado)->get()->pluck('id');
        }else {
            $fits = Fit::where('estado', $estado)->get()->pluck('id');
        }
        $fitUsers = Fit_User::whereIn('id_fit', $fits)->get()->pluck('id_user');;
        $users = User::whereIn('id_user', $fitUsers)->get()->sortBy('nombres')->values()->all();

        return $users;
    }

    public function getListarFitsByprofesor(Request $request){
        if(!$request->ajax()) return redirect('/');

        $user   = Auth::user();
        $estado = $request->estado;
        $escuela = $request->escuela;
        $nivel = $request->nivel;


        $fits = Fit::where('estado', 'like', "%$estado%");

        if ($nivel == 2) $fits->where('id_escuela', $user->id_escuela);

        if ($nivel == 3) {
            $fits->where(function ($fit) use ($user) {
                $fit->where('id_p_guia', '=', "$user->id_user")
                ->orWhere('id_p_co_guia', '=', "$user->id_user");
            });
        }

        if ($escuela) {
            $fits->where('id_escuela', $escuela);
        }
        $fits = $fits->get();
        foreach ($fits as $fit) {
            $alumnos = $fit->getAlumnos();
            $fit->nombres = $alumnos->first()->nombres.' '.$alumnos->first()->apellidos;
            if ($alumnos->count() > 1) {
                foreach ($alumnos->skip(1) as $alumno) {
                    $fit->nombres = $fit->nombres.', '.$alumno->nombres.' '.$alumno->apellidos;
                }
            }
        }

        return ($nivel) ? $fits : [];
    }

    public function getSeleccionarAvance(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdAvance = $request->nIdAvance;

        $nIdAvance = ($nIdAvance == NULL) ? ($nIdAvance = 0) : $nIdAvance;

        $avances = AvancesTesis::find($nIdAvance);
        $avances->ArchivoPdf;
        return $avances;

    }
    public function setRegistrarAvance(Request $request){
        if(!$request->ajax()) return redirect('/');

        $idUser = Auth::user()->id_user;
        $user = User::find($idUser);
        $fit  = Fit_User::Firstwhere('id_user', $idUser)->Fit;
        $fit->User_P_Guia;
        $habilitarEmails  =  Parametro::where('parametro', 'HabilitarEmails')->first()->valor;
        $habilitarEmailSubirAvance  =  Parametro::where('parametro', 'emailSubirAvance')->first()->valor;

        $DatosEmail = new stdClass();
        $DatosEmail->email_pg = $fit->User_P_Guia->email;
        $DatosEmail->titulo = $fit->titulo;
        $DatosEmail->tipo = $fit->tipo;
        $DatosEmail->full_name = $user->nombres . ' ' . $user->apellidos;
        $DatosEmail->fecha = Carbon::now()->format('d-M-Y H:i');

        if ($habilitarEmails && $habilitarEmailSubirAvance) {
            Mail::to($DatosEmail->email_pg)->queue(new MailAvances($DatosEmail));
        }

        $rpta               = new AvancesTesis();
        $rpta->descripcion  = $request->descripcion;
        $rpta->id_archivo   = $request->id_archivo;
        $rpta->id_tesis     = $fit->id;
        $rpta->created_at   = Carbon::now();
        $rpta->updated_at   = Carbon::now();
        $rpta->save();

        $this->reg('Registrar avance', $rpta->id, 'Alumno');
        return $rpta;
    }
    public function setRegistrarFinalPdf(Request $request){
        if(!$request->ajax()) return redirect('/');

        $idUser     = Auth::user()->id_user;
        $idTesis    = Fit::select('id')->where('id_alumno',$idUser)->get();

        $IdArchivo = $request->id_archivo;
        Fit::find($idTesis[0]->id)->update(['id_pdftesis'=>$IdArchivo]);
        $this->reg('Registrar avance final', 'Alumno');
        //NotasPendientes::find($id)->update(['fecha_propuesta'=>$fecha_propuesta]);
    }
    public function setEditarAvance(Request $request){
        if(!$request->ajax()) return redirect('/');

        $id             = $request->id;
        $descripcion    = $request->descripcion;
        $id_archivo     = $request->id_archivo;
        $fecha          = Carbon::now();

        if($id_archivo == 0){
            AvancesTesis::find($id)->update(['descripcion'=>$descripcion, 'created_at' =>$fecha]);

        }else{
            $archivo = AvancesTesis::find($id)->ArchivoPdf;
            $name = last(explode('/', $archivo->path));
            Storage::delete('public/users/'.$name);
            AvancesTesis::find($id)->update(['descripcion'=>$descripcion, 'created_at' =>$fecha, 'id_archivo' =>$id_archivo]);
            $archivo->delete();
        }
        $this->reg('Editar avance', $id, 'Alumno');
    }
    public function setAvanceARevision(Request $request){
        if(!$request->ajax()) return redirect('/');
        $idArchivo = $request->idArchivo;
        $idTesis = $request->idTesis;
        $cambioAvance = ArchivoPdf  ::where('id_fit',"$idTesis")
                                    ->where('tipo_pdf','revision')
                                    ->update(['tipo_pdf'=>'avance_t']);
        if ($request->change) {
            $avanceARevision = ArchivoPdf   ::where('id',"$idArchivo")
                                            ->update(['tipo_pdf'=>'revision']);
        }
        return response()->json(['estado' => 'Cambio validado.'], 200);
    }
    public function getEstadoTesis(Request $request){
        if(!$request->ajax()) return redirect('/');

        $idUser = Auth::user()->id_user;
        $fit_user = Fit_User::Firstwhere('id_user', $idUser);
        if($fit_user){
            return $fit_user->Fit->estado;
        }
        return [];
    }
}
