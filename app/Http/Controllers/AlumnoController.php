<?php

namespace App\Http\Controllers;

use App\Fit;
use App\User;
use App\Fit_User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Mail\MailRegistroFit;
use App\Mail\MailAceptacionFit;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AlumnoController extends Controller
{
    public function setRegistrarAlumno(Request $request){
        if(!$request->ajax()) return redirect('/');

        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            return response()->json(['error' => 'Ingresa un correo válido'], 401);
        }

        $validar = $request->validate([

            'email' =>'required|email|unique:users',
        ]);

        $cNombre = $request->cNombre;
        $cApellido = $request->cApellido;
        $nIdEscuela = $request->nIdEscuela;
        $cCorreo = $request->email;
        $cContrasena = Hash::make($request->cContrasena);
        $oFotografia = $request->oFotografia;

        $cNombre = ($cNombre == NULL) ? ($cNombre = '') : $cNombre;
        $cApellido = ($cApellido == NULL) ? ($cApellido = '') : $cApellido;
        $nIdEscuela = ($nIdEscuela == NULL) ? ($nIdEscuela = '') : $nIdEscuela;
        $cCorreo = ($cCorreo == NULL) ? ($cCorreo = '') : $cCorreo;
        $cContrasena = ($cContrasena == NULL) ? ($cContrasena = '') : $cContrasena;
        $oFotografia = ($oFotografia == NULL) ? ($oFotografia = NULL) : $oFotografia;

        $rpta = DB::select('call sp_alumno_setRegistrarAlumno (?, ?, ?, ?, ?, ?)',
                                                                [
                                                                    $cNombre,
                                                                    $cApellido,
                                                                    $nIdEscuela,
                                                                    $cCorreo,
                                                                    $cContrasena,
                                                                    $oFotografia
                                                                ]);
        return $rpta[0]->nIdUsuario;
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
        // if(!$request->ajax()) return redirect('/');

        $nIdUsuario  = Auth::id();
        $nIdTesis    = $request->nIdTesis;

        //$nIdUsuario  = ($nIdUsuario == NULL) ? ($nIdUsuario = '') : $nIdUsuario;
        //$nIdTesis    = ($nIdTesis == NULL) ? ($nIdTesis = 0) : $nIdTesis;
        /*
        $rpta = DB::select('call sp_alumno_getListarTesis (?, ?)',
                                                                [
                                                                    $nIdUsuario,
                                                                    $nIdTesis
                                                                ]);*/
        // $rpta = DB::table('fit')
        //         ->join('users', 'users.id_user', '=', 'fit.id_profesorguia')
        //         ->leftjoin('vinculaciones', 'vinculaciones.id', '=', 'fit.id_vinculacion')
        //         ->select(   'fit.id', 'fit.id_profesorguia', 'fit.titulo',
        //                     'users.nombres AS pname', 'vinculaciones.nombre AS vname',
        //                     'fit.tipo', 'fit.objetivo', 'fit.contribucion',
        //                     'nombre_int1', 'rut_int1', 'telefono_int1', 'ingreso_int1',
        //                     'email_int1', 'nombre_int2', 'rut_int2', 'email_int2',
        //                     'ingreso_int2', 'telefono_int2', 'fit.estado', 'fit.descripcion',
        //                     'fit.fecha_ultimoramo', 'fit.aprobado_pg',
        //                     'fit.id_alumno AS idAlumno', 'fit.id_vinculacion')
        //         ->where('fit.id', '=', $nIdTesis)
        //         ->orWhere('fit.id_alumno', '=', $nIdUsuario)
        //         ->orWhere('fit.id_profesorguia', '=', $nIdUsuario)
        //         ->get();

        $isStudent = User   ::find($nIdUsuario)
                            ->Users_Roles->first()
                            ->Roles->slug == 'rol.alumno';
        if ($isStudent){
            $fitUser = Fit_User::where('id_user', $nIdUsuario)->get()->pluck('id_fit');
            $fits = Fit::whereIn('id', $fitUser)->get()->all();
        }else{
            $fits = Fit::where('id_p_guia', $nIdUsuario)->get()->all();
        }
        foreach ($fits as $fit) {
            $listUsers = $fit->Fit_User->pluck('id_user');
            $listUsersDetails = User::whereIn('id_user', $listUsers)->get()->all();
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
       /*
        $rpta = DB::select('call sp_alumno_getListarTesis (?, ?)',
                                                                [
                                                                    $nIdUsuario,
                                                                    $nIdTesis
                                                                ]);*/
        // $rpta = DB::table('fit')
        //             ->join('users', 'users.id_user', '=', 'fit.id_profesorguia')
        //             ->leftjoin('vinculaciones', 'vinculaciones.id', '=', 'fit.id_vinculacion')
        //             ->select('fit.id','fit.id_profesorguia','fit.titulo', 'users.nombres AS pname', 'vinculaciones.nombre AS vname', 'fit.tipo', 'fit.objetivo',
        //                         'fit.contribucion', 'nombre_int1', 'rut_int1', 'telefono_int1', 'ingreso_int1', 'email_int1', 'nombre_int2',
        //                         'rut_int2', 'email_int2', 'ingreso_int2', 'telefono_int2', 'fit.estado','fit.descripcion','fit.fecha_ultimoramo',
        //                         'fit.aprobado_pg', 'fit.id_alumno AS idAlumno', 'fit.id_vinculacion')
        //             ->where('fit.id_profesorguia', '<>', $nIdUsuario)
        //             ->get();

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

        $nIdTesis    =$request->nIdTesis;


        // $rpta = DB::table('fit')
        //             ->join('users', 'users.id_user', '=', 'fit.id_profesorguia')
        //             ->leftjoin('vinculaciones', 'vinculaciones.id', '=', 'fit.id_vinculacion')
        //             ->select('fit.id','fit.id_profesorguia','fit.titulo', 'users.nombres AS pname', 'vinculaciones.nombre AS vname', 'fit.tipo', 'fit.objetivo',
        //                         'fit.contribucion', 'nombre_int1', 'rut_int1', 'telefono_int1', 'ingreso_int1', 'email_int1', 'nombre_int2',
        //                         'rut_int2', 'email_int2', 'ingreso_int2', 'telefono_int2', 'fit.estado','fit.descripcion','fit.fecha_ultimoramo',
        //                         'fit.aprobado_pg', 'fit.id_alumno AS idAlumno', 'fit.id_vinculacion')
        //             ->where('fit.id', '=', $nIdTesis)
        //             ->get();

        $fit = Fit::where('id', $nIdTesis)->first();
        $fit->Vinculaciones;
        $fit->User_P_Guia;
        foreach($fit->Fit_User->all() as $fit_user) {
            $fit_user->User->first();
        }
        return $fit;
    }
    public function getListarMiTesis(Request $request){

        if(!$request->ajax()) return redirect('/');

        $nIdUsuario  = Auth::id();
        $nIdTesis    =$request->nIdTesis;

        $nIdUsuario  = ($nIdUsuario == NULL) ? ($nIdUsuario = '') : $nIdUsuario;
        $nIdTesis    = ($nIdTesis == NULL) ? ($nIdTesis = 0) : $nIdTesis;
       /*
        $rpta = DB::select('call sp_alumno_getListarTesis (?, ?)',
                                                                [
                                                                    $nIdUsuario,
                                                                    $nIdTesis
                                                                ]);*/
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

    public function setRegistrarTesis(Request $request){
        // if(!$request->ajax()) return redirect('/');

        $nIdAlumno          = Auth::id();
        $cTitulo            = $request->cTitulo;
        $nIdPg              = $request->nIdPg;
        $nIdVinculacion     = $request->nIdVinculacion;
        $dFechaUR           = $request->dFechaUR;
        $cTipo              = $request->cTipo;
        $cObjetivo          = $request->cObjetivo;
        $cDescripcion       = $request->cDescripcion;
        $cContribucion      = $request->cContribucion;
        $cNombreI1          = $request->cNombreI1;
        $cRutI1             = $request->cRutI1;
        $cTelefonoI1        = $request->cTelefonoI1;
        $cIngresoI1         = $request->cIngresoI1;
        $cEmailI1           = $request->cEmailI1;
        $cNombreI2          = $request->cNombreI2;
        $cRutI2             = $request->cRutI2;
        $cEmailI2           = $request->cEmailI2;
        $cIngresoI2         = $request->cIngresoI2;
        $cTelefonoI2        = $request->cTelefonoI2;


        $nIdAlumno      = ($nIdAlumno == NULL) ? ($nIdAlumno = '') : $nIdAlumno;
        $cTitulo        = ($cTitulo == NULL) ? ($cTitulo = '') : $cTitulo;
        $nIdPg          = ($nIdPg == NULL) ? ($nIdPg = '') : $nIdPg;
        $nIdVinculacion = ($nIdVinculacion == NULL) ? ($nIdVinculacion = NULL) : $nIdVinculacion;
        $dFechaUR       = ($dFechaUR == NULL) ? ($dFechaUR = NULL) : $dFechaUR;
        $cTipo          = ($cTipo == NULL) ? ($cTipo = '') : $cTipo;
        $cObjetivo      = ($cObjetivo == NULL) ? ($cObjetivo = '') : $cObjetivo;
        $cDescripcion   = ($cDescripcion == NULL) ? ($cDescripcion = '') : $cDescripcion;
        $cContribucion  = ($cContribucion == NULL) ? ($cContribucion = '') : $cContribucion;
        $cNombreI1      = ($cNombreI1 == NULL) ? ($cNombreI1 = '') : $cNombreI1;
        $cRutI1         = ($cRutI1 == NULL) ? ($cRutI1 = '') : $cRutI1;
        $cTelefonoI1    = ($cTelefonoI1 == NULL) ? ($cTelefonoI1 = '') : $cTelefonoI1;
        $cIngresoI1     = ($cIngresoI1 == NULL) ? ($cIngresoI1 = '') : $cIngresoI1;
        $cEmailI1       = ($cEmailI1 == NULL) ? ($cEmailI1 = '') : $cEmailI1;
        $cNombreI2      = ($cNombreI2 == NULL) ? ($cNombreI2 = '') : $cNombreI2;
        $cRutI2         = ($cRutI2 == NULL) ? ($cRutI2 = '') : $cRutI2;
        $cEmailI2       = ($cEmailI2 == NULL) ? ($cEmailI2 = '') : $cEmailI2;
        $cIngresoI2     = ($cIngresoI2 == NULL) ? ($cIngresoI2 = '') : $cIngresoI2;
        $cTelefonoI2    = ($cTelefonoI2 == NULL) ? ($cTelefonoI2 = '') : $cTelefonoI2;

        $rpta = DB::select('call sp_alumno_setRegistrarTesis (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)',
                                                                [
                                                                    $nIdAlumno,
                                                                    $cTitulo,
                                                                    $nIdPg,
                                                                    $nIdVinculacion,
                                                                    $dFechaUR,
                                                                    $cTipo,
                                                                    $cObjetivo,
                                                                    $cDescripcion,
                                                                    $cContribucion,
                                                                    $cNombreI1,
                                                                    $cRutI1,
                                                                    $cTelefonoI1,
                                                                    $cIngresoI1,
                                                                    $cEmailI1,
                                                                    $cNombreI2,
                                                                    $cRutI2,
                                                                    $cEmailI2,
                                                                    $cIngresoI2,
                                                                    $cTelefonoI2
                                                                ]);

        $DatosEmail    = DB::table('users')
                              ->select('email as emailpg')
                              ->where('id_user','=', $nIdPg)
                              ->get();
        $DatosEmail[0]->alumno = $cNombreI1;
        $DatosEmail[0]->titulo = $cTitulo;
        $fecha = Carbon::now();
        $DatosEmail[0]->fecha = $fecha;
        Mail::to($DatosEmail[0]->emailpg)->queue(new MailRegistroFit($DatosEmail[0]));
    }
    public function setCambiarEstadoFIT(Request $request){

    if(!$request->ajax()) return redirect('/');

    $nIdTesis   = $request->nIdTesis;
    $cEstadoPg  = $request->cEstadoPg;

    $nIdTesis   = ($nIdTesis == NULL) ? ($nIdTesis = 0) : $nIdTesis;
    $cEstadoPg  = ($cEstadoPg == NULL) ? ($cEstadoPg = 0) : $cEstadoPg;

    $rpta = DB::select('call sp_alumno_setCambiarEstadoFIT (?, ?)',
                                                            [
                                                                $nIdTesis,
                                                                $cEstadoPg
                                                            ]);
    $DatosEmail = DB::table('fit')
                    ->join('users as profesor_guia', 'profesor_guia.id_user', '=', 'fit.id_profesorguia')
                    ->join('users as alumno', 'alumno.id_user', '=', 'fit.id_alumno')
                    ->where('fit.id', '=', $nIdTesis)
                    ->select('alumno.email as emailpg','fit.titulo', DB::raw("CONCAT(profesor_guia.nombres,' ',profesor_guia.apellidos) as full_name"))
                    ->get();
    $fecha = Carbon::now();
    $DatosEmail[0]->fecha = $fecha;
    Mail::to($DatosEmail[0]->emailpg)->queue(new MailAceptacionFit($DatosEmail[0]));
    }
    public function setEditarTesis(Request $request){

        if(!$request->ajax()) return redirect('/');

        $id =   $request->id;
        //$aprobado_pg =   'P';
        Fit::find($id)->update($request->all());
    }
    public function setGenerarDocumento(Request $request){

        $id = $request->nIdTesis;
        $datosfit = DB::table('fit')
                    ->leftjoin('vinculaciones', 'vinculaciones.id', '=','fit.id_vinculacion')
                    ->leftjoin('comisiones', 'comisiones.id_tesis', '=', 'fit.id')
                    ->leftjoin('users as profesor_guia', 'profesor_guia.id_user', '=', 'fit.id_profesorguia')
                    ->join('escuelas', 'escuelas.id', '=', 'profesor_guia.id_escuela')
                    ->leftjoin('users as profesor_1','profesor_1.id_user','=', 'comisiones.id_profesor1')
                    ->leftjoin('users as profesor_2','profesor_2.id_user','=', 'comisiones.id_profesor2')
                    ->join('users', 'users.id_user', '=','fit.id_profesorguia')
                    ->select('nombre_int1', 'rut_int1', 'email_int1', 'ingreso_int1', 'telefono_int1', 'users.nombres', 'users.apellidos','escuelas.nombre as escuela_name',
                                DB::raw("CONCAT(profesor_guia.nombres,' ',profesor_guia.apellidos) as pg_fullname"),
                                DB::raw("CONCAT(profesor_1.nombres,' ',profesor_1.apellidos) as p1_fullname"),
                                DB::raw("CONCAT(profesor_2.nombres,' ',profesor_2.apellidos) as p2_fullname"),
                                'comisiones.p_externo','comisiones.correo_p_externo','comisiones.institucion_p_externo','objetivo', 'contribucion',  'fit.tipo','titulo' )
                    ->where('fit.id', '=', $id)
                    ->get();
                    //return $datosfit;
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
                        ->select('users.id_user',DB::raw("CONCAT(users.nombres,' ',users.apellidos) as fullname"))
                        ->where([
                            ['roles.name', '=', 'Profesor'],
                            ['users.id_user', '<>', $nIdUsuario]
                        ])
                        ->orwhere([
                            ['roles.name', '=', 'Director'],
                            ['users.id_user', '<>', $nIdUsuario]
                        ])
                        ->orwhere([
                            ['roles.name', '=', 'Coordinador'],
                            ['users.id_user', '<>', $nIdUsuario]
                        ])
                        ->get();
        return $profesores;
    }

    public function getListarEstudiantes(Request $request){

        if(!$request->ajax()) return redirect('/');

        $profesores = DB::table('users')
                        ->join('users_roles', 'users_roles.id_user', '=', 'users.id_user')
                        ->join('roles', 'roles.id', '=', 'users_roles.id_roles')
                        ->select('users.id_user',DB::raw("CONCAT(users.nombres,' ',users.apellidos) as fullname"))
                        ->where([
                            ['roles.name', '=', 'Alumno'],
                        ])
                        ->orderBy('users.id_user', 'desc')
                        ->get();
        return $profesores;
    }
    public function setRegistrarTesisfinalizada(Request $request){
        if(!$request->ajax()) return redirect('/');

       /* $cTitulo            = $request->cTitulo;
        $nIdPg              = $request->nIdPg;
        $nIdEst             = $request->nIdEst;
        $nIdVinculacion     = $request->nIdVinculacion;
        $nIdFile            = $request->nIdFile;
        $nIdFile1           = $request->nIdFile1;
        $dFechaUR           = $request->dFechaUR;
        $cTipo              = $request->cTipo;
        $Nota               = $request->Nota;
        $cObjetivo          = $request->cObjetivo;
        $cDescripcion       = $request->cDescripcion;
        $cContribucion      = $request->cContribucion;
        $cNombreI1          = $request->cNombreI1;
        $cRutI1             = $request->cRutI1;
        $cTelefonoI1        = $request->cTelefonoI1;
        $cIngresoI1         = $request->cIngresoI1;
        $cEmailI1           = $request->cEmailI1;
        $cNombreI2          = $request->cNombreI2;
        $cRutI2             = $request->cRutI2;
        $cEmailI2           = $request->cEmailI2;
        $cIngresoI2         = $request->cIngresoI2;
        $cTelefonoI2        = $request->cTelefonoI2;
   */
        $rpta                   = new Fit();
        $rpta->id_alumno        = $request->nIdEst;
        $rpta->id_profesorguia  = $request->nIdPg;
        $rpta->id_vinculacion   = $request->nIdVinculacion;
        $rpta->id_pdftesis      = $request->nIdFile;
        $rpta->id_actadefensa   = $request->nIdFile1;
        $rpta->nota             = $request->Nota;
        $rpta->estado           = 'A';
        $rpta->titulo           = $request->cTitulo;
        $rpta->tipo             = $request->cTipo;
        $rpta->fecha_ultimoramo = $request->dFechaUR;
        $rpta->objetivo         = $request->cObjetivo;
        $rpta->descripcion      = $request->cDescripcion;
        $rpta->contribucion     = $request->cContribucion;
        $rpta->nombre_int1      = $request->cNombreI1;
        $rpta->rut_int1         = $request->cRutI1;
        $rpta->email_int1       = $request->cEmailI1;
        $rpta->ingreso_int1     = $request->cIngresoI1;
        $rpta->telefono_int1    = $request->cTelefonoI1;
        $rpta->nombre_int2      = $request->cNombreI2;
        $rpta->rut_int2         = $request->cRutI2;
        $rpta->email_int2       = $request->cEmailI2;
        $rpta->ingreso_int2     = $request->cIngresoI2;
        $rpta->telefono_int2    = $request->cTelefonoI2;
        $rpta->aprobado_pg      = 'A';
        $rpta->save();

        return $rpta;
    }
    public function getUsersAlumnosParametros(Request $request){
        $alumnoBuscado = [];
        $rpta = User::where('rut', $request->rut)
                    ->orWhere('nombres', $request->nombre)
                    ->orWhere('apellidos', $request->apellido)
                    ->orWhere('email', $request->email)
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
