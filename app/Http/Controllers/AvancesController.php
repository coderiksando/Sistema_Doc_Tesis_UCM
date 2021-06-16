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
use Illuminate\Support\Facades\Storage;

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
        
        $this->reg('Registrar avance', 'Alumno');
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
        $this->reg('Editar avance', 'Alumno');
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
