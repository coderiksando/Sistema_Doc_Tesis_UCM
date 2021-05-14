<?php

namespace App\Http\Controllers;
use App\Fit;
use App\Fit_User;
use App\Bitacoras;
use App\User;
use \stdClass;
use Carbon\Carbon;
use App\Mail\MailBitacoras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BitacorasController extends Controller
{
    public function getListarMisBitacoras(Request $request){
        if(!$request->ajax()) return redirect('/');

        $IdAlumno = Auth::id();
        $MisBitacoras = [];
        $FitUser = Fit_User::Firstwhere('id_user', $IdAlumno);
        if ($FitUser){
            $Fit = $FitUser->Fit;
            if ($Fit) {
                $MisBitacoras = $Fit->Bitacoras->sortByDesc('updated_at')->values()->all();
            }   
        }
        return $MisBitacoras;
    }
    public function getListarBitacorasByAlumno(Request $request){
        if(!$request->ajax()) return redirect('/');

        $IdAlumno   = $request->id_user;
        $IdBitacora = $request->nIdBitacora;
        $Bitacoras = [];

        $FitUser = Fit_User::Firstwhere('id_user', $IdAlumno);
        if ($FitUser){
            $Fit = $FitUser->Fit;
            if ($Fit) {
                $Bitacoras = $Fit->Bitacoras->sortByDesc('updated_at')->values()->all();
            }   
        }

        return $Bitacoras;
    }

    public function setRegistrarBitacora(Request $request){
        if(!$request->ajax()) return redirect('/');

        $IdAlumno   = $request->id_user;
        $DatosEmail = new stdClass();
        $alumno     = User::find($IdAlumno);
        $fit        = Fit_User::Firstwhere('id_user', $IdAlumno)->Fit;
        $fit_users  = $fit->Fit_User;
        $fit->User_P_Guia;

        foreach($fit_users as $fit_user){
            $DatosEmail->email_a = $fit_user->User->email;
            $DatosEmail->titulo = $fit->titulo;
            $DatosEmail->full_name = $fit->User_P_Guia->nombres . ' ' . $fit->User_P_Guia->apellidos;
            $DatosEmail->fecha = Carbon::now();
            Mail::to($DatosEmail->email_a)->queue(new MailBitacoras($DatosEmail));
        }

        // $DatosEmail = DB::table('fit')
        //                     ->join('users as profesor_guia', 'profesor_guia.id_user', '=', 'fit.id_profesorguia')
        //                     ->join('users as alumno', 'alumno.id_user', '=', 'fit.id_alumno')
        //                     ->where('fit.id', '=', $idTesis[0]->id)
        //                     ->select('alumno.email as email_a','fit.titulo', DB::raw("CONCAT(profesor_guia.nombres,' ',profesor_guia.apellidos) as full_name"))
        //                     ->get();
        // $DatosEmail[0]->fecha = Carbon::now();

        // Mail::to($DatosEmail[0]->email_a)->queue(new MailBitacoras($DatosEmail[0]));

        $Bitacora               = new Bitacoras();
        $Bitacora->comentario   = $request->Comentario;
        $Bitacora->acuerdo      = $request->Acuerdo;
        $Bitacora->fecha        = Carbon::now();
        $Bitacora->id_tesis     = $fit->id;
        $Bitacora->save();

        return $Bitacora;
    }
    public function setEditarBitacora(Request $request){
        if(!$request->ajax()) return redirect('/');
        
        $id         = $request->nIdBitacora;
        $comentario = $request->comentario;
        $acuerdo    = $request->acuerdo;
        $fecha      = Carbon::now();

        Bitacoras::find($id)->update(['comentario'=>$comentario,'acuerdo'=>$acuerdo,'fecha'=>$fecha]);

        return;
    }
}
