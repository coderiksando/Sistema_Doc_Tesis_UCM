<?php

namespace App\Http\Controllers;
use App\NotasPendientes;
use App\Fit;
use App\Fit_User;
use App\User;
use App\Users_Roles;
use App\Parametro;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailNotaPendiente;
use Debugbar;
use \stdClass;

class NotasPendientesController extends Controller
{
    public function setRegistrarNotaP(Request $request){
        if(!$request->ajax()) return redirect('/');

        $idAlumno = Auth::id();
        $fit    = $this->getFit();
        $fecha = $request->fecha_propuesta;

        $habilitarEmails  =  Parametro::where('parametro', 'HabilitarEmails')->first()->valor;
        $habilitarEmailNotaPendiente  =  Parametro::where('parametro', 'emailNotaPendiente')->first()->valor;

        $NotaP                      = new NotasPendientes();
        $NotaP->id_tesis            = $fit->id;
        $NotaP->fecha_propuesta     = $fecha;
        $NotaP->fecha_presentacion  = Carbon::now();
        $NotaP->save();

        // $Coordinador = DB::table('users')
        //             ->join('users_roles', 'users_roles.id_user', '=', 'users.id_user')
        //             ->join('roles', 'roles.id', '=', 'users_roles.id_roles')
        //             ->select('email as emailcoordinador','users.id_user',DB::raw("CONCAT(users.nombres,' ',users.apellidos) as fullname"))
        //             ->where('roles.name', '=', 'Coordinador')
        //             ->get();

        $Coordinador = User::find(Users_Roles::firstWhere('id_roles', '5')->id_user);

        $DatosEmail = new stdClass();
        $DatosEmail->emailpg = $fit->User_P_Guia->email;
        $DatosEmail->titulo = $fit->titulo;
        $DatosEmail->rut_int1 = Auth::user()->rut;
        $DatosEmail->full_name = Auth::user()->nombres.' '.Auth::user()->apellidos;
        $DatosEmail->fechapropuesta = Carbon::parse($fecha)->format('d-M-Y');
        $fechaSistema = Carbon::now()->format('d-M-Y H:i');
        $DatosEmail->fecha = $fechaSistema;

        if ($habilitarEmails && $habilitarEmailNotaPendiente) {
           Mail::to([$DatosEmail->emailpg,$Coordinador->email])->queue(new MailNotaPendiente($DatosEmail));
        }
        
        return $NotaP;
    }
    public function setAsignarNotaP(Request $request){
        if(!$request->ajax()) return redirect('/');

        $id_notapendiente = $request->id_notapendiente;

        $idAlumno   = Auth::id();
        $idTesis    = Fit::select('id')->where('id_alumno',$idAlumno)->get();
        $tesis      = DB::table('fit')
                            ->where('id', $idTesis[0]->id)
                            ->update(['id_notapendiente' => $id_notapendiente]);
        return $tesis;
    }
    public function getMiNotaP(Request $request){
        if(!$request->ajax()) return redirect('/');

        $fit = $this->getFit();
        $idAlumno = Auth::id();
        $NotaP = NULL;
        // $NotaP    = DB::table('fit')
        //                 ->join('notaspendientes', 'notaspendientes.id_tesis', '=', 'fit.id')
        //                 ->join('users', 'users.id_user', '=', 'fit.id_alumno')
        //                 ->select('notaspendientes.id','fecha_presentacion', 'fecha_propuesta', 'fecha_prorroga', 'notaspendientes.estado',DB::raw("CONCAT(users.nombres,' ',users.apellidos) as full_name"))
        //                 ->where('fit.id_alumno', '=', $idAlumno)
        //                 ->get();
        if ($fit) {
            $NotaP = $fit->NotasPendientes;
            $alumnos = $fit->getAlumnos();
            if ($NotaP) {
                $NotaP->alumnos = $alumnos;
            }
        }

        return ($NotaP) ? [$NotaP] : [];
    }
    public function getListarNotasPendientes(Request $request){
        if(!$request->ajax()) return redirect('/');

        $user = Auth::user();
        $idEscuela = $user->id_escuela;
        $nombre = $request->nombre;
        $apellido = $request->apellido;
        $fechaInicio = ($request->dFechaInicio == NULL) ? '0001/01/01' : Carbon::parse($request->dFechaInicio)->startOfDay();
        $fechaFin    = ($request->dFechaFin == NULL) ? '9999/12/31' : Carbon::parse($request->dFechaFin)->endOfDay();
        $estado      = $request->estado;

        $nombre     = ($nombre == NULL) ? '' : $nombre;
        $apellido   = ($apellido == NULL) ? '' : $apellido;

        $idFits = Fit::where('id_escuela', $idEscuela)->where('estado', 'D')->where('id_p_guia', $user->id_user);

        if ($nombre || $apellido) {
            $users = User::where('nombres', 'LIKE', "%$nombre%")
                           ->where('apellidos', 'LIKE', "%$apellido%")->get()->pluck('id_user');
            $fitUser = Fit_User::whereIn('id_user', $users)->get()->pluck('id_fit');
            $idFits->whereIn('id', $fitUser);
        }
        
        $idFits = $idFits->pluck('id');

        $users  = User::where('nombres', 'like', "%$nombre%")->where('apellidos', 'like', "%$apellido%")->get()->pluck('id');
        $notasP = NotasPendientes::whereIn('id_tesis', $idFits)
                                ->whereBetween('fecha_propuesta', [$fechaInicio, $fechaFin]);

        if ($estado == 1) {
            $notasP = $notasP->whereNull('fecha_prorroga');
        }
        if ($estado == 2) {
            $notasP = $notasP->whereNotNull('fecha_prorroga');
        }
        $notasP = $notasP->get();

        foreach ($notasP as $nota) {
            $nota->alumnos = Fit::find($nota->id_tesis)->getAlumnos();
        }

        

        return $notasP;

    }

    public function getNotaPendienteById(Request $request){
        $nIdNotaP = $request->nIdNotaP;

        $notaP = NotasPendientes::find($nIdNotaP);

        return $notaP;
    }
    public function getListarNotasPendientesbyEscuela(Request $request){
        //if(!$request->ajax()) return redirect('/');
        $user           = Auth::user();
        $estado         = $request->estado;
        $idEscuela      = $user->id_escuela;
        $nombre         = $request->nombre;
        $apellido       = $request->apellido;
        $fechaInicio    = ($request->dFechaInicio == NULL) ? '0001/01/01' : Carbon::parse($request->dFechaInicio)->startOfDay();
        $fechaFin       = ($request->dFechaFin == NULL) ? '9999/12/31' : Carbon::parse($request->dFechaFin)->endOfDay();
        $nivelAcceso        =  $request->nivelAcceso;


        $nombre     = ($nombre == NULL) ? '' : $nombre;
        $apellido   = ($apellido == NULL) ? '' : $apellido;

        $idFits = Fit::where('estado', 'D');

        if ($nivelAcceso) {
           $idFits = $idFits->where('id_escuela', $idEscuela);
        }

        if ($nombre || $apellido) {
            $users = User::where('nombres', 'LIKE', "%$nombre%")
                           ->where('apellidos', 'LIKE', "%$apellido%")->get()->pluck('id_user');
            $fitUser = Fit_User::whereIn('id_user', $users)->get()->pluck('id_fit');
            $idFits->whereIn('id', $fitUser);
        }
        
        $idFits = $idFits->pluck('id');

        $users  = User::where('nombres', 'like', "%$nombre%")->where('apellidos', 'like', "%$apellido%")->get()->pluck('id');
        $notasP = NotasPendientes::whereIn('id_tesis', $idFits)
                                ->whereBetween('fecha_propuesta', [$fechaInicio, $fechaFin]);


        if ($estado == 1) {
            $notasP = $notasP->whereNull('fecha_prorroga');
        }
        if ($estado == 2) {
            $notasP = $notasP->whereNotNull('fecha_prorroga');
        }
        $notasP = $notasP->get();

        foreach ($notasP as $nota) {
            $nota->alumnos = Fit::find($nota->id_tesis)->getAlumnos();
        }

        return $notasP;
    }

    public function getListarNotasPendientesByAlumno(Request $request){
        if(!$request->ajax()) return redirect('/');

        $IdProfesor       = Auth::id();
        $nIdNotaP       = $request->nIdNotaP;
        $estado         = $request->estado;
        $dFechaInicio   = $request->dFechaInicio;
        $dFechaFin      = $request->dFechaFin;

        $nIdNotaP       = ($nIdNotaP == NULL) ? ($nIdNotaP = 0) : $nIdNotaP;
        $NotasP = DB::table('fit')
                        ->leftjoin('notaspendientes', 'notaspendientes.id', '=','fit.id_notapendiente')
                        ->Where('notaspendientes.estado', '=', $estado)
                        ->OrWhere('notaspendientes.id', '=', $nIdNotaP)
                        ->OrWhere('fit.id_profesorguia', '=', $IdProfesor)
                        ->OrWhere('notaspendientes.id', '=', 0)
                        ->orWhereBetween('notaspendientes.fecha_propuesta', [$dFechaInicio, $dFechaFin])
                        ->select('notaspendientes.id','fecha_presentacion', 'fecha_propuesta', 'fecha_prorroga', 'notaspendientes.estado')
                        ->get();
        return $NotasP;
    }
    public function setEditarNotaP(Request $request){
        if(!$request->ajax()) return redirect('/');

        $id                 = $request->nIdNotaP;
        $fecha_propuesta    = $request->fecha_propuesta;

        NotasPendientes::find($id)->update(['fecha_propuesta'=>$fecha_propuesta]);
    }
    public function setIngresarProrroga(Request $request){
        if(!$request->ajax()) return redirect('/');

        $id             = $request->nIdNotaP;
        $fecha_prorroga = $request->fecha_prorroga;

        NotasPendientes::find($id)->update(['fecha_prorroga'=>$fecha_prorroga]);
    }
}
