<?php

namespace App\Http\Controllers;
use App\Fit;
use App\Fit_User;
use App\Bitacoras;
use App\User;
use App\Parametro;
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

        if ($IdBitacora) {
            return Bitacoras::find($IdBitacora);
        }

        $FitUser = Fit_User::Firstwhere('id_user', $IdAlumno);
        if ($FitUser){
            $Fit = $FitUser->Fit;
            if ($Fit) {
                $Bitacoras = $Fit->Bitacoras->sortByDesc('fecha')->values()->all();
            }
        }

        return $Bitacoras;
    }
    
    public function getListarBitacorasByFit(Request $request){
        if(!$request->ajax()) return redirect('/');

        $Bitacoras = [];

        $Fit = Fit::find($request->fit);
        $Bitacoras = $Fit->Bitacoras->sortByDesc('fecha')->values()->all();


        return $Bitacoras;
    }

    public function setRegistrarBitacora(Request $request){
        if(!$request->ajax()) return redirect('/');

        $fit        = Fit::find($request->fit);
        $fit_users  = $fit->Fit_User;
        $DatosEmail = new stdClass();  
        $fit->User_P_Guia;
        $habilitarEmails  =  Parametro::where('parametro', 'HabilitarEmails')->first()->valor;
        $habilitarEmailCrearActaReunion  =  Parametro::where('parametro', 'emailCrearActaReunion')->first()->valor;

        foreach($fit_users as $fit_user){
            $DatosEmail->email_a = $fit_user->User->email;
            $DatosEmail->titulo = $fit->titulo;
            $DatosEmail->tipo = $fit->tipo;
            $DatosEmail->full_name = $fit->User_P_Guia->nombres . ' ' . $fit->User_P_Guia->apellidos;
            $DatosEmail->fecha = Carbon::now()->format('d-M-Y H:i');

            if ($habilitarEmails && $habilitarEmailCrearActaReunion) {
                Mail::to($DatosEmail->email_a)->queue(new MailBitacoras($DatosEmail));
            }
            
        }

        $Bitacora               = new Bitacoras();
        $Bitacora->acuerdo      = $request->Acuerdo;
        $Bitacora->fecha        = Carbon::now();
        $Bitacora->id_tesis     = $fit->id;
        $Bitacora->save();

        $this->reg('Registrar Bitácora', $Bitacora->id,  $request->session()->get('rol'));

        return $Bitacora;
    }
    public function setEditarBitacora(Request $request){
        if(!$request->ajax()) return redirect('/');

        $id         = $request->nIdBitacora;
        $acuerdo    = $request->acuerdo;
        $fecha      = Carbon::now();

        Bitacoras::find($id)->update(['acuerdo'=>$acuerdo,'fecha'=>$fecha]);
        $this->reg('Editar Bitácora', $id,  $request->session()->get('rol'));
        return;
    }
}
