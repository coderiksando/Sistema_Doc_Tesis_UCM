<?php

namespace App\Http\Controllers;

use App\Fit;
use App\User;
use App\Fit_User;
use App\Users_Roles;
use App\ArchivoPdf;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Mail\MailRegistroFit;
use App\Mail\MailAceptacionFit;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use \stdClass;
use Debugbar;

class AlumnoController extends Controller
{
    public function setRegistrarAlumno(Request $request){
        if(!$request->ajax()) return redirect('/');

        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            return response()->json(['error' => 'Ingresa un correo válido.'], 400);
        }
        $validarRut = User::where('rut', $request->cRut)->first();
        if ($validarRut) {
            return response()->json(['error' => 'Rut ingresado ya existente.'], 400);
        }
        $validar = $request->validate([
            'email' =>'required|email|unique:users',
        ]);
        $cNombre = $request->cNombre;
        $cApellido = $request->cApellido;
        $cRut = $request->cRut;
        $cCorreo = $request->email;
        $password = Hash::make($request->cContrasena);

        $user = new User;
        $user->rut = $cRut;
        $user->nombres = $cNombre;
        $user->apellidos = $cApellido;
        $user->email = $cCorreo;
        $user->password = $password;
        $user->save();

        $rol_user = new Users_Roles;
        $rol_user->id_user = $user->id_user;
        $rol_user->id_roles = 2;
        $rol_user->save();
        $this->reg('Registro de usuario y rol', '0', 'Alumno', $user->id_user);
        return [$user, $rol_user];
    }
    public function setEditarRolAlumno(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdUsuario = $request->nIdUsuario;
        $nIdRol = $request->nIdRol;

        $nIdUsuario = ($nIdUsuario == NULL) ? ($nIdUsuario = '') : $nIdUsuario;
        $nIdRol = ($nIdRol == NULL) ? ($nIdRol = '') : $nIdRol;

        $rpta = DB::select('call sp_alumno_setEditarRolAlumno (?, ?)',
                                                                [
                                                                    $nIdUsuario,
                                                                    $nIdRol
                                                                ]);
    }
    public function getListarTesis(Request $request){
        //if(!$request->ajax()) return redirect('/');

        $nIdUsuario  = Auth::id();
        $nIdTesis    = $request->nIdTesis;
        $rol = $request->session()->get('rol');

        if ($rol == 'Alumno'){
            $fitUser = Fit_User::where('id_user', $nIdUsuario)->get()->pluck('id_fit');
            $fits = Fit::whereIn('id', $fitUser)->get()->all();
        }
        if ($rol == 'Profesor'){
            $fits = Fit::where('id_p_guia', $nIdUsuario)->whereIn('aprobado_pg', ['P', 'A', 'V'])->get()->all();
        }
        if ($rol == 'Director' || $rol == 'Coordinador'){
            $fits = Fit::whereIn('aprobado_pg', ['A', 'V'])->get()->all();
        }

        foreach ($fits as $fit) {
            $listUsers = $fit->Fit_User->pluck('id_user');
            $listUsersDetails = User::whereIn('id_user', $listUsers)->get()->all();
            $fit->constancia = ArchivoPdf::where('id_fit', $fit->id)->firstWhere('tipo_pdf', 'constancia_t');
            $fit->listUsers = $listUsersDetails;
        }

        return $fits;
    }
    public function getListarAllTesis(Request $request){

        if(!$request->ajax()) return redirect('/');

        $nIdUsuario  = Auth::id();
        $nIdTesis    =$request->nIdTesis;

        //$nIdUsuario  = ($nIdUsuario == NULL) ? ($nIdUsuario = '') : $nIdUsuario;
        //$nIdTesis    = ($nIdTesis == NULL) ? ($nIdTesis = 0) : $nIdTesis;

        $fits = Fit::all();
        foreach($fits as $fit) {
            foreach($fit->Fit_User->all() as $fit_user) {
                $fit_user->User->first();
            }
        }

        return $fits;
    }
    public function getListarTesisView(Request $request){
        if(!$request->ajax()) return redirect('/');
        Debugbar::info($request);

        $nIdTesis = $request->nIdTesis;

        $fit = Fit::where('id', $nIdTesis)->first();
        $fit->Vinculaciones;
        $fit->User_P_Guia;
        $fit->User_P_Coguia;
        if ($fit->Comisiones) {
            $fit->Comisiones->UserP1;
            $fit->Comisiones->UserP2;
        }
        if ($fit->Fit_User) {
            foreach($fit->Fit_User->all() as $fit_user) {
                $fit_user->User->first();
            }
        }
        return $fit;
    }
    public function getListarMiTesis(Request $request){

        if(!$request->ajax()) return redirect('/');

        $nIdUsuario  = Auth::id();
        $nIdTesis    =$request->nIdTesis;

        $nIdUsuario  = ($nIdUsuario == NULL) ? ($nIdUsuario = '') : $nIdUsuario;
        $nIdTesis    = ($nIdTesis == NULL) ? ($nIdTesis = 0) : $nIdTesis;

        $rpta = DB::table('fit')
                    ->join('users', 'users.id_user', '=', 'fit.id_profesorguia')
                    ->leftjoin('vinculaciones', 'vinculaciones.id', '=', 'fit.id_vinculacion')
                    ->select('fit.id','fit.titulo', 'users.nombres AS pname', 'vinculaciones.nombre AS vname', 'fit.tipo', 'fit.objetivo',
                                'fit.contribucion', 'nombre_int1', 'rut_int1', 'telefono_int1', 'ingreso_int1', 'email_int1', 'nombre_int2',
                                'rut_int2', 'email_int2', 'ingreso_int2', 'telefono_int2', 'fit.estado', 'fit.aprobado_pg', 'fit.id_alumno AS idAlumno')
                    ->where('fit.id', '=', $nIdTesis)
                    ->orWhere('fit.id', '=', 0)
                    ->orWhere('fit.id_alumno', '=', $nIdUsuario)
                    ->get();
        return $rpta;
    }
    public function getListarTesisTerminadas(Request $request){
        if(!$request->ajax()) return redirect('/');

        $cAlumno        = ($request->cAlumno   == NULL)   ? ($cAlumno = '')     : $request->cAlumno;
        $cProfesor      = ($request->cProfesor == NULL)   ? ($cProfesor = '')   : $request->cProfesor;
        $nIdEscuela     = ($request->nIdEscuela  == NULL) ? ($nIdEscuela = '')  : $request->nIdEscuela;
        $cTitulo        = ($request->cTitulo     == NULL) ? ($cTitulo = '')     : $request->cTitulo;
        $cEstadoTesis   = ($request->cEstadoTesis== NULL) ? ($cEstadoTesis = ''): $request->cEstadoTesis;
        $dFechaInicio   = ($request->dFechaInicio== NULL) ? ($dFechaInicio = '1000-01-01'): $request->dFechaInicio;
        $dFechaFin      = ($request->dFechaFin   == NULL) ? ($dFechaFin = '3000-01-01')   : $request->dFechaFin;

        // modulo de reparacion de fits que no tengan escuela o no tengan vinculación, se ejecuta solo 1 vez (por lo general)
        $repair = Fit::whereNull('id_escuela')->get();
        if ($repair) {
            foreach ($repair as $fit) {
                $fit->id_escuela = 1;
                $fit->save();
            }
        }
        $repair = Fit::whereNull('id_vinculacion')->get();
        if ($repair) {
            foreach ($repair as $fit) {
                $fit->id_vinculacion = 1;
                $fit->save();
            }
        }

        $fits=DB::table('fit')
                ->leftjoin('fit_user', 'fit.id', '=', 'fit_user.id_fit')
                ->leftjoin('users as pGuia','pGuia.id_user', '=', 'fit.id_p_guia')
                ->leftjoin('users as alumno','alumno.id_user', '=', 'fit_user.id_user')
                ->select('fit.id')
                ->where(DB::raw("CONCAT(alumno.nombres,' ',alumno.apellidos)"), 'like', "%$cAlumno%")
                ->where(DB::raw("CONCAT(pGuia.nombres,' ',pGuia.apellidos)"), 'like', "%$cProfesor%")
                ->where('fit.titulo','like',"%$cTitulo%")
                ->where('fit.id_escuela','like',"%$nIdEscuela%")
                ->where('fit.estado','like',"%$cEstadoTesis%")
                ->where('fit.estado','<>',"D")
                ->whereBetween('fit.updated_at', [$dFechaInicio,$dFechaFin])
                ->get()
                ->pluck('id');

        $fitsDetails = Fit::findMany($fits);
        foreach ($fitsDetails as $fit) {
            $fit->User_P_Guia;
            $fit->Escuela;
            if ($fit->Fit_User) {
                $fit->getAlumnos();
            }
            $fit->ArchivoPdf;
        }

        return $fitsDetails;
    }

    public function setRegistrarTesis(Request $request){
        if(!$request->ajax()) return redirect('/');
        $fit = $request->data;
        $fit = (object) $fit;
        $firstUser = $fit->cUsers[0];
        $firstUser = (object) $firstUser;
        $firstUserEscuela = $firstUser->id_escuela;
        $firstUserEscuela = (int) $firstUserEscuela;

        DB::transaction(function () use ($fit, $firstUserEscuela) {
            $registroFit = new Fit;
            $registroFit->id_p_guia = $fit->nIdPg;
            $registroFit->id_p_co_guia = $fit->nIdCoPg;
            $registroFit->id_escuela = $firstUserEscuela;
            $registroFit->id_vinculacion = $fit->nIdVinculacion;
            $registroFit->titulo = $fit->cTitulo;
            $registroFit->tipo = $fit->cTipo;
            $registroFit->objetivo_general = $fit->cObjetivoGeneral;
            $registroFit->objetivo_especifico = $fit->cObjetivoEspecifico;
            $registroFit->descripcion = $fit->cDescripcion;
            $registroFit->contribucion = $fit->cContribucion;
            $registroFit->estado = 'D';
            $registroFit->aprobado_pg = 'P';
            $registroFit->save();

            foreach($fit->cUsers as $user) {
                $userToRegister = new Fit_User;
                $user = (object) $user;
                $userToRegister->id_user = $user->id_user;
                $userToRegister->id_fit = $registroFit->id;
                $userToRegister->save();
            }
        });

        $DatosEmail = DB::table('users')
                        ->select('email as emailpg')
                        ->where('id_user','=', $fit->nIdPg)
                        ->get();
        $alumnoPrincipal = (object)$fit->cUsers[0];
        $DatosEmail[0]->alumno = $alumnoPrincipal->nombres;
        $DatosEmail[0]->titulo = $fit->cTitulo;
        $fecha = Carbon::now();
        $DatosEmail[0]->fecha = $fecha;
        Mail::to($DatosEmail[0]->emailpg)->queue(new MailRegistroFit($DatosEmail[0]));
    }
    public function setCambiarEstadoFIT(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdTesis   = $request->nIdTesis;
        $cEstadoPg  = $request->cEstadoPg;
        $rol = $request->session()->get('rol');

        $nIdTesis   = ($nIdTesis == NULL) ? ($nIdTesis = 0) : $nIdTesis;
        $cEstadoPg  = ($cEstadoPg == NULL) ? ($cEstadoPg = 0) : $cEstadoPg;

        if ($cEstadoPg == 'A' && ($rol == 'Director' || $rol == 'Coordinador')) {
            $cEstadoPg = 'V';
        }

        // ingresando la información nueva de fit
        $registroFit = Fit::find($nIdTesis);
        $registroFit->aprobado_pg = $cEstadoPg;
        $registroFit->motivo_pg = $request->motivo;
        $registroFit->update();
        // Envío de email a estudiantes con el informe de su estado FIT
        $fit = Fit::find($nIdTesis);
        $fecha = Carbon::now();
        $datosEmail = [];
        $i = 0;
        $estadoFit = ($fit->aprobado_pg == 'A') ? ('aprobado') : ('rechazado');
        foreach($fit->Fit_User as $item) {
            $user = $item->User;
            $datoInsertado = new stdClass();
            $datoInsertado->emailpg = $item->User->email;
            $datoInsertado->titulo = $fit->titulo;
            $datoInsertado->full_name = ($fit->User_P_Guia->nombres) . ' ' . ($fit->User_P_Guia->apellidos);
            $datoInsertado->fecha = $fecha;
            $datoInsertado->estado = $estadoFit;
            $datoInsertado->motivo = $request->motivo;
            array_push($datosEmail, $datoInsertado);
            Mail::to($datosEmail[$i]->emailpg)->queue(new MailAceptacionFit($datosEmail[$i]));
            $i++;
        }
    }
    public function setEditarTesis(Request $request){
        if(!$request->ajax()) return redirect('/');
        $fit = $request;

        DB::transaction(function () use ($fit) {
            $registroFit = Fit::find($fit->id);
            $registroFit->id_p_guia = $fit->id_profesorguia;
            $registroFit->id_p_co_guia = $fit->id_profesor_coguia;
            $registroFit->id_vinculacion = $fit->id_vinculacion;
            $registroFit->titulo = $fit->titulo;
            $registroFit->tipo = $fit->tipo;
            $registroFit->objetivo_general = $fit->objetivoGeneral;
            $registroFit->objetivo_especifico = $fit->objetivoEspecifico;
            $registroFit->descripcion = $fit->descripcion;
            $registroFit->contribucion = $fit->contribucion;
            $registroFit->estado = 'D';
            $registroFit->aprobado_pg = 'P';
            $registroFit->update();

            // eliminando usuarios participantes al fit y reasignandolos
            $rows_fit_user = Fit_User::where('id_fit', $fit->id)->delete();
            foreach($fit->users as $user) {
                $user = (object) $user;
                $userToRegister = new Fit_User;
                $userToRegister->id_user = $user->id_user;
                $userToRegister->id_fit = $fit->id;
                $userToRegister->save();
            }
        });
        return response()->json(['estado' => 'ok'], 200);
    }
    public function setGenerarDocumento(Request $request){
        $id = $request->nIdTesis;
        $datosfit = Fit::find($id);
        $datosfit->Vinculaciones;
        $datosfit->Comisiones;
        $datosfit->User_P_Guia;
        $datosfit->User_P_Guia->Escuelas;
        $datosfit->Fit_User;
        foreach($datosfit->Fit_User as $fit_user) {
            $fit_user->User;
            $fit_user->User->Escuelas;
        }
        if ($datosfit->Comisiones) {
            $datosfit->Comisiones->UserP1;
            $datosfit->Comisiones->UserP2;
        }
        $pdf = PDF::loadView('reportes.tesis.pdf.verfit',[
           'datosfit' => $datosfit
        ]);

        return $pdf->download('invoice.pdf');
    }

    public function getListarProfesores(Request $request){

        if(!$request->ajax()) return redirect('/');
        $nIdUsuario  = Auth::id();

        $profesores = DB::table('users')
                        ->join('users_roles', 'users_roles.id_user', '=', 'users.id_user')
                        ->join('roles', 'roles.id', '=', 'users_roles.id_roles')
                        ->select('users.id_user',DB::raw("CONCAT(users.nombres,' ',users.apellidos) as fullname"), 'users.id_escuela')
                        ->where('users.state', 'A')
                        ->where([
                            ['roles.name', '=', 'Profesor'],
                            ['users.id_user', '<>', $nIdUsuario]
                        ])
                        ->where('state', 'A')
                        ->orderBy('fullname')->get();
        return $profesores;
    }

    public function getAllUserRoll(Request $request){
        if(!$request->ajax()) return redirect('/');
        $alumnos = User::where('state', 'A')->get();
        foreach ($alumnos as $alumno) {
            foreach ($alumno->Users_Roles as $alumnoRol) {
                $alumnoRol->Roles;
            }
        }
        return $alumnos;
    }

    public function getUsersAlumnosParametros(Request $request){
        $alumnoBuscado = [];
        $rpta = User::where('rut', 'like',"%$request->rut%")
                    ->where('nombres','like', "%$request->nombre%")
                    ->where('apellidos','like', "%$request->apellido%")
                    ->where('email','like', "%$request->email%")
                    ->where('state', 'A')
                    ->get()->all();
        foreach($rpta as $user){
            foreach($user->Users_Roles->all() as $user_rol){
                if ($user_rol->Roles->slug == 'rol.alumno') {
                    array_push($alumnoBuscado, $user);
                }
            }
        }
        return $alumnoBuscado;
    }
}
