<?php

namespace App\Http\Controllers;
use App\NotasPendientes;
use App\Fit;
use App\User;
use App\Users_Roles;
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
        $DatosEmail->fechapropuesta = $fecha;
        $fechaSistema = Carbon::now();
        $DatosEmail->fecha = $fechaSistema;

        // $DatosEmail = DB::table('fit')
        //             ->join('users as profesor_guia', 'profesor_guia.id_user', '=', 'fit.id_p_guia')
        //             ->join('users as alumno', 'alumno.id_user', '=', 'fit.id_alumno')
        //             ->where('fit.id', '=', $idTesis[0]->id)
        //             ->select('fit.rut_int1','profesor_guia.email as emailpg','fit.titulo', DB::raw("CONCAT(alumno.nombres,' ',alumno.apellidos) as full_name"))
        //             ->get();
        
        
        Mail::to([$DatosEmail->emailpg,$Coordinador->email])->queue(new MailNotaPendiente($DatosEmail));
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
        Debugbar::info($fit);

        // $NotaP    = DB::table('fit')
        //                 ->join('notaspendientes', 'notaspendientes.id_tesis', '=', 'fit.id')
        //                 ->join('users', 'users.id_user', '=', 'fit.id_alumno')
        //                 ->select('notaspendientes.id','fecha_presentacion', 'fecha_propuesta', 'fecha_prorroga', 'notaspendientes.estado',DB::raw("CONCAT(users.nombres,' ',users.apellidos) as full_name"))
        //                 ->where('fit.id_alumno', '=', $idAlumno)
        //                 ->get();

        $NotaP = $fit->NotasPendientes;
        $user = User::find($idAlumno);
        if ($NotaP) {
            $NotaP->full_name = $user->nombres.' '.$user->apellidos;
        }

        return ($NotaP) ? [$NotaP] : [];
    }
    public function getListarNotasPendientes(Request $request){
        if(!$request->ajax()) return redirect('/');

        $IdProfesor       = Auth::id();
        $nIdNotaP       = $request->nIdNotaP;
        $estado         = $request->estado;
        $dFechaInicio   = $request->dFechaInicio;
        $dFechaFin      = $request->dFechaFin;

        $nIdNotaP       = ($nIdNotaP == NULL) ? ($nIdNotaP = 0) : $nIdNotaP;
        $NotasP = DB::table('fit')
                        ->join('notaspendientes', 'notaspendientes.id_tesis', '=','fit.id')
                        ->join('users', 'users.id_user', '=', 'fit.id_alumno')
                        ->Where('notaspendientes.estado', '=', $estado)
                        ->OrWhere('notaspendientes.id', '=', $nIdNotaP)
                        ->OrWhere('fit.id_profesorguia', '=', $IdProfesor)
                        ->OrWhere('notaspendientes.id', '=', 0)
                        ->orWhereBetween('notaspendientes.fecha_propuesta', [$dFechaInicio, $dFechaFin])
                        ->select('notaspendientes.id','fecha_presentacion', 'fecha_propuesta', 'fecha_prorroga', 'notaspendientes.estado',DB::raw("CONCAT(users.nombres,' ',users.apellidos) as full_name"))
                        ->get();
        return $NotasP;
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
