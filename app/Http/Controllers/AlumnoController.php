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
use \stdClass;

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
        if(!$request->ajax()) return redirect('/');

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
        $fit->User_P_Coguia;
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
        if(!$request->ajax()) return redirect('/');
        $fit = $request->data;
        $fit = (object) $fit;

        DB::transaction(function () use ($fit) {
            $registroFit = new Fit;
            $registroFit->id_p_guia = $fit->nIdPg;
            $registroFit->id_p_co_guia = $fit->nIdCoPg;
            $registroFit->id_vinculacion = $fit->nIdVinculacion;
            $registroFit->titulo = $fit->cTitulo;
            $registroFit->tipo = $fit->cTipo;
            $registroFit->objetivo = $fit->cObjetivo;
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


        // $nIdAlumno          = Auth::id();
        // $cTitulo            = $request->cTitulo;
        // $nIdPg              = $request->nIdPg;
        // $nIdVinculacion     = $request->nIdVinculacion;
        // $dFechaUR           = $request->dFechaUR;
        // $cTipo              = $request->cTipo;
        // $cObjetivo          = $request->cObjetivo;
        // $cDescripcion       = $request->cDescripcion;
        // $cContribucion      = $request->cContribucion;
        // $cNombreI1          = $request->cNombreI1;
        // $cRutI1             = $request->cRutI1;
        // $cTelefonoI1        = $request->cTelefonoI1;
        // $cIngresoI1         = $request->cIngresoI1;
        // $cEmailI1           = $request->cEmailI1;
        // $cNombreI2          = $request->cNombreI2;
        // $cRutI2             = $request->cRutI2;
        // $cEmailI2           = $request->cEmailI2;
        // $cIngresoI2         = $request->cIngresoI2;
        // $cTelefonoI2        = $request->cTelefonoI2;


        // $nIdAlumno      = ($nIdAlumno == NULL) ? ($nIdAlumno = '') : $nIdAlumno;
        // $cTitulo        = ($cTitulo == NULL) ? ($cTitulo = '') : $cTitulo;
        // $nIdPg          = ($nIdPg == NULL) ? ($nIdPg = '') : $nIdPg;
        // $nIdVinculacion = ($nIdVinculacion == NULL) ? ($nIdVinculacion = NULL) : $nIdVinculacion;
        // $dFechaUR       = ($dFechaUR == NULL) ? ($dFechaUR = NULL) : $dFechaUR;
        // $cTipo          = ($cTipo == NULL) ? ($cTipo = '') : $cTipo;
        // $cObjetivo      = ($cObjetivo == NULL) ? ($cObjetivo = '') : $cObjetivo;
        // $cDescripcion   = ($cDescripcion == NULL) ? ($cDescripcion = '') : $cDescripcion;
        // $cContribucion  = ($cContribucion == NULL) ? ($cContribucion = '') : $cContribucion;
        // $cNombreI1      = ($cNombreI1 == NULL) ? ($cNombreI1 = '') : $cNombreI1;
        // $cRutI1         = ($cRutI1 == NULL) ? ($cRutI1 = '') : $cRutI1;
        // $cTelefonoI1    = ($cTelefonoI1 == NULL) ? ($cTelefonoI1 = '') : $cTelefonoI1;
        // $cIngresoI1     = ($cIngresoI1 == NULL) ? ($cIngresoI1 = '') : $cIngresoI1;
        // $cEmailI1       = ($cEmailI1 == NULL) ? ($cEmailI1 = '') : $cEmailI1;
        // $cNombreI2      = ($cNombreI2 == NULL) ? ($cNombreI2 = '') : $cNombreI2;
        // $cRutI2         = ($cRutI2 == NULL) ? ($cRutI2 = '') : $cRutI2;
        // $cEmailI2       = ($cEmailI2 == NULL) ? ($cEmailI2 = '') : $cEmailI2;
        // $cIngresoI2     = ($cIngresoI2 == NULL) ? ($cIngresoI2 = '') : $cIngresoI2;
        // $cTelefonoI2    = ($cTelefonoI2 == NULL) ? ($cTelefonoI2 = '') : $cTelefonoI2;

        // $rpta = DB::select('call sp_alumno_setRegistrarTesis (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)',
        //                                                         [
            //                                                             $nIdAlumno,
            //                                                             $cTitulo,
            //                                                             $nIdPg,
            //                                                             $nIdVinculacion,
            //                                                             $dFechaUR,
            //                                                             $cTipo,
            //                                                             $cObjetivo,
            //                                                             $cDescripcion,
            //                                                             $cContribucion,
            //                                                             $cNombreI1,
            //                                                             $cRutI1,
            //                                                             $cTelefonoI1,
        //                                                             $cIngresoI1,
        //                                                             $cEmailI1,
        //                                                             $cNombreI2,
        //                                                             $cRutI2,
        //                                                             $cEmailI2,
        //                                                             $cIngresoI2,
        //                                                             $cTelefonoI2
        //                                                         ]);

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

        $nIdTesis   = ($nIdTesis == NULL) ? ($nIdTesis = 0) : $nIdTesis;
        $cEstadoPg  = ($cEstadoPg == NULL) ? ($cEstadoPg = 0) : $cEstadoPg;

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
            $registroFit->objetivo = $fit->objetivo;
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
        $rpta = User::where('rut', 'like',"%$request->rut%")
                    ->orWhere('nombres','like', "%$request->nombre%")
                    ->orWhere('apellidos','like', "%$request->apellido%")
                    ->orWhere('email','like', "%$request->email%")
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
