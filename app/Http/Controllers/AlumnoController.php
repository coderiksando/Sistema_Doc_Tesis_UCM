<?php

namespace App\Http\Controllers;

use App\Fit;
use App\User;
use App\Fit_User;
use App\Users_Roles;
use App\ArchivoPdf;
use App\Comisiones;
use App\Parametro;
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
        if(!$request->ajax()) return redirect('/');

        $nIdUsuario     = Auth::id();
        $cNombre        = $request->nombre;
        $cApellido      = $request->apellido;
        $cEstado        = $request->estado;
        $nRut           = $request->rut;
        $dFechaInicio   = Carbon::parse($request->fechaSt)->startOfDay();
        $dFechaFin      = Carbon::parse($request->fechaEn)->endOfDay();
        $nIdEscuela     = $request->nIdEscuela;
        $rol = $request->session()->get('rol');

        if ($rol == 'Alumno'){
            $fitUser = Fit_User::where('id_user', $nIdUsuario)->get()->pluck('id_fit');
            $fits = Fit::whereIn('id', $fitUser);
        }elseif($rol == 'Profesor'){
            $fits = Fit::where(function ($fit) use ($nIdUsuario) {
                $fit->where('id_p_guia', '=', "$nIdUsuario")
                    ->orWhere('id_p_co_guia', '=', "$nIdUsuario");
            });
        }elseif($rol == 'Director'){
            $fits = Fit::where('id_escuela', Auth::user()->id_escuela);
        }else{
            $fits = Fit::where('id', '>' , 0);
        }

//      Filtros de busqueda
        if ($cNombre || $cApellido || $nRut) {
            $users = User::where('nombres', 'LIKE', "%$cNombre%")
                           ->where('apellidos', 'LIKE', "%$cApellido%")
                           ->where('rut', 'LIKE', "%$nRut%")->get()->pluck('id_user');

            $fitUser = Fit_User::whereIn('id_user', $users)->get()->pluck('id_fit');
            $fits->whereIn('id', $fitUser);
            Debugbar::info($fits->get());
        }
        $estado1 = NULL;
        $estado2 = NULL;
        if (is_array($cEstado)){
            $estado1 = array_key_exists('0', $cEstado);
            $estado2 = array_key_exists('1', $cEstado);
        }
        if ($estado1 || $estado2) {
            if ($cEstado[0] != 'EA') {
                if ($cEstado[0] != '' && $cEstado[1] != '')
                $fits = $fits->where('aprobado_pg', $cEstado[0])->where('estado', $cEstado[1]);
                if ($cEstado[1] != '')
                $fits = $fits->where('estado', $cEstado[1]);
            }
            if ($cEstado[0] == 'EA') {
                $fits = $fits->where('aprobado_pg', $cEstado[0]);
            }
        }
        if ($request->fechaSt) {
            $fits = $fits->whereBetween('created_at', [$dFechaInicio, $dFechaFin]);
        }
        if ($nIdEscuela) {
            $fits = $fits->where('id_escuela',"$nIdEscuela");
        }
        $fits = $fits->get()->sortByDesc('updated_at')->values()->all();
        foreach ($fits as $fit) {
            $listUsers = $fit->Fit_User->pluck('id_user');
            $listUsersDetails = User::whereIn('id_user', $listUsers)->get()->all();
            $fit->constancia = ArchivoPdf::where('id_fit', $fit->id)->firstWhere('tipo_pdf', 'constancia_t');
            $fit->listUsers = $listUsersDetails;
            $fit->Comisiones;
            $fit->Escuela;
            $fit->Fecha_defensa;
        }
        return $fits;
    }
    public function getListarTesisView(Request $request){
        if(!$request->ajax()) return redirect('/');

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
        Debugbar::info($fit);
        return $fit;
    }
    public function getTesisById(Request $request){
        if(!$request->ajax()) return redirect('/');
        $id = $request->id;
        $fit = Fit::find($id);
        return $fit;
    }
    public function getTesisByIdExtendida(Request $request){
        if(!$request->ajax()) return redirect('/');
        $id = $request->id;
        $fit = Fit::find($id);
        $fit->Vinculaciones;
        $fit->Fecha_defensa;
        if ($fit->Comisiones) {
            $fit->Comisiones->UserP1;
            $fit->Comisiones->UserP2;
        }
        if ($fit->User_P_Guia) $fit->User_P_Guia->Escuela;
        if ($fit->User_P_Coguia) $fit->User_P_Coguia->Escuela;
        $fit->Escuela;
        $fit->getAlumnos();
        foreach ($fit->Fit_User as $fit_user) {
            $fit_user->User->Escuelas->Facultad;
        }
        return $fit;
    }
    public function getListarMiTesis(Request $request){
        if(!$request->ajax()) return redirect('/');
        $nIdUsuario  = Auth::id();
        $rol = $request->session()->get('rol');

        if($rol == 'Coordinador'){
            $fitsCoordinador = Fit::all()->pluck('id');
            return $fitsCoordinador;
        }
        if($rol == 'Director'){
            $fitsDirector = Fit::where('id_escuela', Auth::user()->id_escuela)->get()->pluck('id');
            return $fitsDirector;
        }
        $fitsAlumno = Fit_User::where('id_user', $nIdUsuario)->get()->pluck('id_fit');
        $fitsProf = Fit::where(function ($fit) use ($nIdUsuario) {
            $fit->where('id_p_guia', '=', "$nIdUsuario")
                ->orWhere('id_p_co_guia', '=', "$nIdUsuario");
        })->get()->pluck('id');
        $fitsComision = Comisiones::where(function ($comision) use ($nIdUsuario) {
            $comision   ->where('id_profesor1',"$nIdUsuario")
                        ->orWhere('id_profesor2',"$nIdUsuario");
        })->get()->pluck('id_tesis');
        $fits = $fitsAlumno->concat($fitsProf);
        $fits = $fits->concat($fitsComision);


        return $fits;
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
                ->where('fit.estado','like',"%$cEstadoTesis%")
                ->where('fit.aprobado_pg',"V")
                ->whereBetween('fit.created_at', [$dFechaInicio,$dFechaFin]);
        if ($nIdEscuela) $fits = $fits->where('fit.id_escuela','=',"$nIdEscuela");
        $fits = $fits   ->get()
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
        $habilitarEmails  =  Parametro::where('parametro', 'HabilitarEmails')->first()->valor;
        $habilitarEmailCrearFID  =  Parametro::where('parametro', 'emailCrearFID')->first()->valor;

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
            $registroFit->fecha = Carbon::now();
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
        $DatosEmail[0]->alumno = $alumnoPrincipal->nombres.' '.$alumnoPrincipal->apellidos;
        $DatosEmail[0]->titulo = $fit->cTitulo;
        $fecha = Carbon::now()->format('d-M-Y H:i');
        $DatosEmail[0]->fecha = $fecha;

        if ($habilitarEmails && $habilitarEmailCrearFID) {
            Mail::to($DatosEmail[0]->emailpg)->queue(new MailRegistroFit($DatosEmail[0]));
        }
    }
    public function setCambiarEstadoFIT(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdTesis   = $request->nIdTesis;
        $cEstadoPg  = $request->cEstadoPg;
        $rol = $request->session()->get('rol');

        $habilitarEmails  =  Parametro::where('parametro', 'HabilitarEmails')->first()->valor;
        $habilitarEmailAceptarFormulario  =  Parametro::where('parametro', 'emailAceptarFormulario')->first()->valor;

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
        $fecha = Carbon::now()->format('d-m-Y H:i');
        $datosEmail = [];
        $i = 0;
        $estadoFit = ($fit->aprobado_pg == 'A' || $fit->aprobado_pg == 'V') ? ('aprobado') : ('rechazado');
        foreach($fit->Fit_User as $item) {
            $user = $item->User;
            $datoInsertado = new stdClass();
            $datoInsertado->emailpg = $item->User->email;
            $datoInsertado->titulo = $fit->titulo;
            $datoInsertado->full_name = (Auth::user()->nombres) . ' ' . (Auth::user()->apellidos);
            $datoInsertado->fecha = $fecha;
            $datoInsertado->estado = $estadoFit;
            $datoInsertado->motivo = $request->motivo;
            $datoInsertado->rol = $rol;
            $datoInsertado->tipo = $fit->tipo;
            array_push($datosEmail, $datoInsertado);
            if ($habilitarEmails && $habilitarEmailAceptarFormulario) {
                Mail::to($datosEmail[$i]->emailpg)->queue(new MailAceptacionFit($datosEmail[$i]));
            }

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
    public function setEditarEstadoTesis(Request $request){
        if(!$request->ajax()) return redirect('/');
        $idTesis            = $request->idTesis;
        $estado             = $request->estado;
        $fit                = Fit::find($idTesis);
        $fit->estado        = 'D';
        $fit->aprobado_pg   = $estado;
        $fit->update();
        return response()->json(['msg' => 'Tesis editada correctamente'], 200);
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
            $fit_user->User->Escuelas->Facultad;
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
        $allTeacher = $request->allTeacher;

        $profesores = DB::table('users')
                        ->join('users_roles', 'users_roles.id_user', '=', 'users.id_user')
                        ->join('roles', 'roles.id', '=', 'users_roles.id_roles')
                        ->select('users.id_user',DB::raw("CONCAT(users.nombres,' ',users.apellidos) as fullname"), 'users.id_escuela')
                        ->where('users.state', 'A');
        if ($allTeacher) $profesores = $profesores->where('roles.name', '=', 'Profesor');
        else $profesores = $profesores->where([
                            ['roles.name', '=', 'Profesor'],
                            ['users.id_user', '<>', $nIdUsuario]
                        ]);
        $profesores = $profesores   ->where('state', 'A')
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
                    ->whereNotNull('id_escuela')
                    ->whereNotNull('direccion')
                    ->whereNotNull('telefono')
                    ->get()->all();
        foreach($rpta as $user){
            $user->Fit_User;
            foreach($user->Users_Roles->all() as $user_rol){
                if ($user_rol->Roles->slug == 'rol.alumno') {
                    array_push($alumnoBuscado, $user);
                }
            }
        }
        return $alumnoBuscado;
    }

    public function getRevisionesComision(Request $request){
        $fit = $this->getFit();
        $revisiones = $fit->Revision_Comision;
        foreach ($revisiones as $item) {
            if ($fit->id_p_guia != $item->id_user) {                                    // *Comentario del desarrollador* //
                $item->nombre = $item->User->nombres.' '.$item->User->apellidos;        // Dado que el profesor externo no esta registrado como usuario
            }else{                                                                      // en el sistema, el encargado de registrar su revision sera el profesor guia,
                $item->nombre = $fit->Comisiones->p_externo;                            // por lo tanto, cuando el id del profesor que ha subido la revision coindcida
            }                                                                           // con el id del profesor guia, el algoritmo buscara los datos correspondientes
            $item->ArchivoPdf;                                                          // al profesor externo para presentarlos, dichos datos estan almacenados unicamente en la tabla comision.
        }
        return $revisiones;
    }


    public function getFidEnDesarrollo(Request $request){
        $nIdUsuario  = Auth::id();
        $fidsId = Fit_User::where('id_user', $nIdUsuario)->get()->pluck('id_fit');
        $fids = Fit::whereIn('id', $fidsId)->where('estado', 'D')->where('aprobado_pg', '!=', 'EA')->get();

        return $fids;
    }
}
