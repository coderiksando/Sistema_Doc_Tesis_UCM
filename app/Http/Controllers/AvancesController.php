<?php

namespace App\Http\Controllers;

use App\AvancesTesis;
use App\Fit;
use App\Fit_User;
use App\User;
use \stdClass;
use App\Mail\MailAvances;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AvancesController extends Controller
{
    public function getListarAvances(Request $request){
        if(!$request->ajax()) return redirect('/');

        $idUser    = Auth::id();
        $nIdAvance = $request->nIdAvance;
        
        $idUser    = ($idUser == NULL) ? ($idUser = 0) : $idUser;
        $nIdAvance = ($nIdAvance == NULL) ? ($nIdAvance = 0) : $nIdAvance;
        $FitUser     = Fit_User::Firstwhere('id_user', $idUser);
        $AvancesTesis = [];
        if($FitUser){
            $Fit = $FitUser->Fit;
            $AvancesTesis = $Fit->AvancesTesis->sortByDesc('updated_at')->values()->all();      
            foreach($AvancesTesis as $avance){
                $avance->ArchivoPdf;
            }
        }
        return $AvancesTesis;
    }
    public function getListarAvancesByAlumno(Request $request){
        if(!$request->ajax()) return redirect('/');

        $idAlumno = $request->id_user;
        $FitUser  = Fit_User::Firstwhere('id_user', $idAlumno);
        $AvancesTesis = [];
        if($FitUser){
            $Fit = $FitUser->Fit;
            $AvancesTesis = $Fit->AvancesTesis->sortByDesc('updated_at')->values()->all();      
            foreach($AvancesTesis as $avance){
                $avance->ArchivoPdf;
            }
        }
        return $AvancesTesis;
    }
    public function getListarAlumnosByprofesor(Request $request){
        if(!$request->ajax()) return redirect('/');

        $idUser     = Auth::id();
        $nIdAvance  = $request->nIdAvance;
        
        $idUser     = ($idUser == NULL) ? ($idUser = 0) : $idUser;
        $nIdAvance  = ($nIdAvance == NULL) ? ($nIdAvance = 0) : $nIdAvance;
        $Users = [];

        $Fit = Fit::Firstwhere('id_p_guia', $idUser);

        if ($Fit) {
            $FitUsers = $Fit->Fit_User;
            foreach($FitUsers as $FitUser){
                array_push($Users, $FitUser->User);
            }

        }

        return $Users;
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
        $DatosEmail = new stdClass();
        $DatosEmail->email_pg = $fit->User_P_Guia->email;
        $DatosEmail->titulo = $fit->titulo;
        $DatosEmail->full_name = $user->nombres . ' ' . $user->apellidos;
        $DatosEmail->fecha = Carbon::now();

        Mail::to($DatosEmail->email_pg)->queue(new MailAvances($DatosEmail));
   
        $rpta               = new AvancesTesis();
        $rpta->descripcion  = $request->descripcion;
        $rpta->id_archivo   = $request->id_archivo;
        $rpta->id_tesis     = $fit->id;
        $rpta->created_at   = Carbon::now();
        $rpta->updated_at   = Carbon::now();
        $rpta->save(); 
        
        return $rpta;
    }
    public function setRegistrarFinalPdf(Request $request){
        if(!$request->ajax()) return redirect('/');

        $idUser     = Auth::user()->id_user;
        $idTesis    = Fit::select('id')->where('id_alumno',$idUser)->get();

        $IdArchivo = $request->id_archivo;
        Fit::find($idTesis[0]->id)->update(['id_pdftesis'=>$IdArchivo]);
        //NotasPendientes::find($id)->update(['fecha_propuesta'=>$fecha_propuesta]);
    }
    public function setEditarAvance(Request $request){
        if(!$request->ajax()) return redirect('/');
        
        $id             = $request->id;
        $descripcion    = $request->descripcion;
        $id_archivo     = $request->id_archivo;
        $fecha          = Carbon::now();

        //$id_archivo     = ($id_archivo == 0) ? ($id_archivo = 0) : $id_archivo;

        if($id_archivo == 0){
            AvancesTesis::find($id)->update(['descripcion'=>$descripcion, 'created_at' =>$fecha]);
            
        }else{
            AvancesTesis::find($id)->update(['descripcion'=>$descripcion, 'created_at' =>$fecha, 'id_archivo' =>$id_archivo]);
        }
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
