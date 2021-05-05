<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailNotafinal;
use App\User;
use App\Fit;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SecretariaController extends Controller
{
    public function getListarAlumnos(Request $request){
        if(!$request->ajax()) return redirect('/');

        $idUser     = Auth::id();
        //$IdEscuela  = User::select('id_escuela')->where('id_user', $idUser)->get();

        $rut        = $request->nRut;
        $nombre     = $request->cNombre;
        $estado     = $request->cEstado_tesis;
        $IdEscuela  = $request->nIdEscuela;
        $EstadoAlumno  = $request->nEstadoAlumno;

        $rut        = ($rut == NULL) ? ($rut = '') : $rut;
        $nombre     = ($nombre == NULL) ? ($nombre = '') : $nombre;
        $estado     = ($estado == NULL) ? ($estado = '') : $estado;
        $IdEscuela  = ($IdEscuela == NULL) ? ($IdEscuela = '') : $IdEscuela;
        $EstadoAlumno  = ($EstadoAlumno == NULL) ? ($EstadoAlumno = '') : $EstadoAlumno;

        $alumnos = DB::table('users')
                        ->join('fit', 'fit.id_alumno', '=', 'users.id_user')
                        ->leftjoin('pdftesis', 'pdftesis.id', '=', 'fit.id_actadefensa')
                        ->select('fit.estado as estado_tesis', 'fit.id as id_tesis',DB::raw("CONCAT(users.nombres,' ',users.apellidos) as full_name"),'fit.rut_int1','pdftesis.path')
                        ->where('users.id_escuela', 'like', "%{$IdEscuela}%")
                        ->where([[(function($query) use ($nombre,$IdEscuela,$rut,$EstadoAlumno){
                            $query
                            ->where('fit.rut_int1', 'like', "%{$rut}%")
                            ->where('fit.estado', 'like', "%{$EstadoAlumno}%")
                            ->where(DB::raw("CONCAT(users.nombres,' ',users.apellidos)"), 'like', "%{$nombre}%")  ;
                            })]])
                        ->get();
        return $alumnos;
    }
    public function setGenerarMemoRevision(Request $request){

        $id        = $request->nIdTesis;
        $logo      = public_path('img/logo_ucm_marca.png');

        $datosmemo = DB::table('fit')
                        ->leftjoin('vinculaciones', 'vinculaciones.id', '=','fit.id_vinculacion')
                        ->leftjoin('comisiones', 'comisiones.id_tesis', '=','fit.id')
                        ->join('users as alumno', 'alumno.id_user', '=', 'fit.id_alumno')
                        ->join('users as profesor_guia', 'profesor_guia.id_user', '=', 'fit.id_profesorguia')
                        ->join('escuelas', 'escuelas.id', '=', 'profesor_guia.id_escuela')
                        ->join('users as profesor_1','profesor_1.id_user','=', 'comisiones.id_profesor1')
                        ->leftjoin('users as profesor_2','profesor_2.id_user','=', 'comisiones.id_profesor2')
                        ->select('escuelas.nombre as escuelaname','comisiones.id','comisiones.id_profesor1', 'comisiones.id_profesor2', 'comisiones.p_externo', 'fit.id_profesorguia as profe_guia',
                                DB::raw("CONCAT(alumno.nombres,' ',alumno.apellidos) as Anombres"),
                                DB::raw("CONCAT(profesor_guia.nombres,' ',profesor_guia.apellidos) as Pnombres"),
                                DB::raw("CONCAT(profesor_1.nombres,' ',profesor_1.apellidos) as P1nombres"),
                                DB::raw("CONCAT(profesor_2.nombres,' ',profesor_2.apellidos) as P2nombres"),
                                'fit.id as id_fit','fit.titulo', 'fit.rut_int1')
                        ->where('fit.id', '=', $id)
                        ->get();
        $fechainicial = Carbon::now();            
        $datosmemo[0]->fechainicial = $fechainicial->toFormattedDateString();  
        $fechafinal   = Carbon::now();
        $fechafinal   = $fechafinal->addDay(21);
        $datosmemo[0]->fechafinal = $fechafinal->format('d-m-Y');
        $pdf          = PDF::loadView('reportes.tesis.pdf.memorevision',[
                                    'datosmemo' => $datosmemo,
                                    'logo' => $logo,
        ]);
        return $pdf->download('invoice.pdf');
    }
    public function setSubirActa(Request $request){
        if(!$request->ajax()) return redirect('/');
        
        $id         = $request->id_tesis;
        $id_archivo = $request->id_archivo;
        
        Fit::find($id)->update(['id_actadefensa'=>$id_archivo]);
    }
    public function setRegistrarNota(Request $request){
        if(!$request->ajax()) return redirect('/');

        $id         = $request->id_tesis;
        $nota       = $request->nota;

        $DatosEmail = DB::table('fit')
                    ->join('users as profesor_guia', 'profesor_guia.id_user', '=', 'fit.id_profesorguia')
                    ->join('users as alumno', 'alumno.id_user', '=', 'fit.id_alumno')
                    ->where('fit.id', '=', $id)
                    ->select('profesor_guia.email as emailpg','alumno.email as email','fit.titulo', DB::raw("CONCAT(alumno.nombres,' ',alumno.apellidos) as full_name"))
                    ->get();
        $fecha = Carbon::now();
        $DatosEmail[0]->fecha = $fecha;
        $DatosEmail[0]->nota = $nota;

        if($nota >= 4){
            $estado = 'A';
            $DatosEmail[0]->estadonota = 'Aprobo';
        }else{
            $estado = 'R';
            $DatosEmail[0]->estadonota = 'Reprobo';
        }

        Fit::find($id)->update(['nota'=>$nota,'estado'=>$estado]);
   
        Mail::to([$DatosEmail[0]->emailpg,$DatosEmail[0]->email])->queue(new MailNotafinal($DatosEmail[0]));
    }
}
