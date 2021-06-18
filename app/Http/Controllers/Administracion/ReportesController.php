<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Fit;
use App\ArchivoPdf;
use App\Log;
use Illuminate\Support\Facades\DB;
use App\Exports\TesisExport;
use Maatwebsite\Excel\Facades\Excel;
use Debugbar;

class ReportesController extends Controller
{
    public function getListarTesisReporte(Request $request){
        if(!$request->ajax()) return redirect('/');

        $rut                = $request->nRut;
        $idescuela          = $request->nIdEscuela;
        $estado_notap       = $request->cEstadoNotap;
        $idprofesor         = $request->nIdProfesor;
        $estado             = $request->cEstadoTesis;
        $dFechaInicio       = $request->dFechaInicio;
        $dFechaFin          = $request->dFechaFin;

        $rut                = ($rut == NULL) ?          ($rut = '')         : $rut;
        $idescuela          = ($idescuela == NULL) ?    ($idescuela = '')   : $idescuela;
        $estado_notap       = ($estado_notap == NULL) ? ($estado_notap = ''): $estado_notap;
        $idprofesor         = ($idprofesor == NULL) ?   ($idprofesor = '')  : $idprofesor;
        $estado             = ($estado == NULL) ?       ($estado = '')      : $estado;
        $dFechaInicio       = ($dFechaInicio == NULL) ? ($dFechaInicio = '2000-01-01'): $dFechaInicio;
        $dFechaFin          = ($dFechaFin == NULL) ?    ($dFechaFin = '2100-01-01')   : $dFechaFin;

        $reportdata = Fit::select('fit.id','nombre_int1', 'rut_int1', 'email_int1', 'ingreso_int1', 'bitacoras.fecha as fecha_bitacora',
                                    'bitacoras.comentario as comentario_bitacora','telefono_int1','fit.nombre_int2','fit.rut_int2',  DB::raw("CONCAT(profesor_guia.nombres,' ',profesor_guia.apellidos) as nombre_pt"),
                                    'objetivo', 'contribucion', 'fit.tipo as tipo_trabajo','titulo', 'avancestesis.created_at as fecha_avance',
                                    'vinculaciones.nombre AS namevinculacion', 'fit.estado','escuelas.nombre as escuela_nom','fit.created_at as fecha_inscripcion','alumno.id_user as IDalumno',
                                    'notaspendientes.fecha_propuesta as fecha_notap','notaspendientes.fecha_prorroga as prorroga_notap', 'notaspendientes.estado as estado_notap','fit.fecha_ultimoramo')
                            ->leftjoin('bitacoras', function ($query) {
                                $query->on('bitacoras.id_tesis','=', 'fit.id')
                                ->WhereRaw('bitacoras.id IN (select MAX(a2.id) from bitacoras as a2 join fit as u2 on u2.id = a2.id_tesis group by u2.id)');
                            })
                            ->leftjoin('avancestesis', function ($query) {
                                $query->on('avancestesis.id_tesis','=', 'fit.id')
                                ->WhereRaw('avancestesis.id IN (select MAX(a2.id) from avancestesis as a2 join fit as u2 on u2.id = a2.id_tesis group by u2.id)');
                            })
                            ->leftjoin('vinculaciones', 'vinculaciones.id', '=','fit.id_vinculacion')
                            ->leftjoin('notaspendientes', 'notaspendientes.id_tesis', '=', 'fit.id')
                            ->leftjoin('users as profesor_guia', 'profesor_guia.id_user', '=', 'fit.id_profesorguia')
                            ->leftjoin('users as alumno', 'alumno.id_user', '=', 'fit.id_alumno')
                            ->leftjoin('escuelas', 'escuelas.id','=','alumno.id_escuela')

                            ->where([[(function($query) use ($estado_notap){
                                    $query
                                        ->orwhere('notaspendientes.estado', 'like', "%$estado_notap%")
                                        ->orwhere('notaspendientes.estado','=', null)  ;   
                            })]])
                            
                            //
                            ->Where('fit.rut_int1', 'like', "%$rut%")
                            ->Where('alumno.id_escuela', 'like', "%$idescuela%")
                            ->Where('profesor_guia.id_user', 'like', "%$idprofesor%")
                            ->Where('fit.estado', 'like', "$estado%")

                            ->WhereBetween('fit.fecha_ultimoramo', [$dFechaInicio, $dFechaFin])
                            ->get();
        return $reportdata;
    }
    public function getListarTesisHome(Request $request){
        if(!$request->ajax()) return redirect('/');

        $titulo             = $request->cTitulo;
        $idescuela          = $request->nIdEscuela;
        $idprofesor         = $request->nIdProfesor;
        

        $titulo             = ($titulo == NULL) ?          ($titulo = '')         : $titulo;
        $idescuela          = ($idescuela == NULL) ?    ($idescuela = '')   : $idescuela;
        $idprofesor         = ($idprofesor == NULL) ?   ($idprofesor = '')  : $idprofesor;
        

        //$archivosPdf = ArchivoPdf::where('tipo_pdf', 'final_t')->get();

        // $reportdata = $archivosPdf->map(function ($item){
        //     //$item->Fit->User_P_Guia;
        //     return collect($item)
        //             ->merge(['titulo'=>$item->Fit->titulo])
        //             ->merge($item->Fit->User_P_Guia)
        //             ->merge(['escuela'=>$item->Fit->User_P_Guia->Escuelas->nombre]);
        // });

       $archivosPdf =  ArchivoPdf::select('fit.titulo', 'path', 'users.nombres', 'users.apellidos', 'escuelas.nombre', 'escuelas.id', 'users.id_user')
                                ->join('fit', 'id_fit', '=', 'fit.id')
                                ->join('users', 'fit.id_p_guia', '=', 'users.id_user')
                                ->join('escuelas', 'users.id_escuela', '=', 'escuelas.id')
                                ->where('tipo_pdf', 'final_t');

        $reportdata = $archivosPdf->where('titulo', 'like', "%$titulo%")
                                   ->where('id_user','like', "%$idprofesor%")
                                   ->where('escuelas.id','like', "%$idescuela%")
                                   ->get();

        return $reportdata;

    }
    public function getListarLogs(Request $request){
        //if(!$request->ajax()) return redirect('/');

        $actividad   = $request->cActividad;
        $idUsuario   = $request->nIdUsuario;
        $rol         = $request->cRol;
        $fechaInicio = $request->dFechaInicio;
        $fechaFin = $request->dFechaFin;
        

        $actividad     = ($actividad == NULL)   ? ($actividad = '')   : $actividad;
        $idUsuario     = ($idUsuario == NULL)   ? ($idUsuario = '')   : $idUsuario;
        $rol           = ($rol == NULL)         ? ($rol = '')         : $rol;
        $fechaInicio   = ($fechaInicio == NULL) ? ($fechaInicio = '2000-01-01') : $fechaInicio;
        $fechaFin      = ($fechaFin == NULL)    ? ($fechaFin = '2100-01-01')    : $fechaFin;

        $logs = Log::select('actividad', 'rol', 'ip', 'target', 'logs.created_at as fecha', 'users.nombres', 'users.apellidos')
                   ->join('users', 'logs.user_id', 'users.id_user')
                   ->where('actividad', 'like', "%$actividad%")
                   ->where('user_id', 'like', "%$idUsuario%")
                   ->where('rol', 'like', "%$rol%")
                   ->WhereBetween('logs.created_at', [$fechaInicio, $fechaFin])->get();
        
        return $logs;
    }   

    public function export(Request $request){
        if(!$request->ajax()) return redirect('/');

        $listTesis = json_decode($request->listTesis);
        return (new TesisExport)->getTesis($listTesis)->download('invoices.xlsx');
        //return Excel::download(new UsersExport, 'users.xlsx');
    }
    public function getListarProfesorByEscuela(Request $request){
        if(!$request->ajax()) return redirect('/');
        
        $escuela     = $request->nIdEscuela;

        $escuela     = ($escuela == NULL) ? ($escuela = '') : $escuela;

        $profesores = DB::table('users')
                        ->join('users_roles', 'users_roles.id_user', '=', 'users.id_user')
                        ->join('roles', 'roles.id', '=', 'users_roles.id_roles')
                        ->select('users.id_user', DB::raw("CONCAT(users.nombres,' ',users.apellidos) as fullname"))
                        ->where('roles.name', 'Profesor')
                        ->where('users.id_escuela', 'like', "%$escuela%")
                        ->orderBy('fullname')->get();
        return $profesores;
    }
}
