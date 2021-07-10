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
use Carbon\Carbon;
use Debugbar;

class ReportesController extends Controller
{
    public function getListarTesisReporte(Request $request){
        if(!$request->ajax()) return redirect('/');

        $rut                = $request->nRut;
        $idescuela          = $request->nIdEscuela;
        $idFacultad         = $request->nIdFacultad;
        $estado_notap       = $request->cEstadoNotap;
        $idprofesor         = $request->nIdProfesor;
        $estado             = $request->cEstadoTesis;
        $dFechaInicio       = $request->dFechaInicio;
        $dFechaFin          = $request->dFechaFin;

        $rut                = ($rut == NULL) ?          ($rut = '')         : $rut;
        $idescuela          = ($idescuela == NULL) ?    ($idescuela = '')   : $idescuela;
        $idFacultad         = ($idFacultad == NULL) ?    ($idFacultad = '')   : $idFacultad;
        $estado_notap       = ($estado_notap == NULL) ? ($estado_notap = ''): $estado_notap;
        $idprofesor         = ($idprofesor == NULL) ?   ($idprofesor = '')  : $idprofesor;
        $estado             = ($estado == NULL) ?       ($estado = '')      : $estado;
        $dFechaInicio       = ($dFechaInicio == NULL) ? ($dFechaInicio = ''): $dFechaInicio;
        $dFechaFin          = ($dFechaFin == NULL) ?    ($dFechaFin = '')   : $dFechaFin;

        $fits = Fit ::where('estado', 'like', "$estado%")
                    ->get();
        foreach ($fits as $fit) {
            $fit->Bitacoras;
            $fit->AvancesTesis;
            $fit->NotasPendientes;
            $fit->Vinculaciones;
            $fit->Comisiones;
            $fit->User_P_Guia;
            $fit->User_P_Guia->Escuelas;
            $fit->User_P_Coguia;
            $fit->getAlumnos();
        }
        $key=0;
        foreach ($fits as $fit) {
            $missing = False;
            if ($idescuela) {
                if ($fit->User_P_Guia->Escuelas->id != $idescuela) $missing = True;
            }
            if ($idFacultad) {
                if ($fit->User_P_Guia->Escuelas->Facultad->id != $idFacultad) $missing = True;
            }
            if ($estado_notap) {
                if ($fit->NotasPendientes) {
                    if ($fit->NotasPendientes->estado != $estado_notap) $missing = True;
                } else {
                    $missing = True;
                }
            }
            if ($idprofesor) {
                if ($fit->User_P_Guia->id_user != $idprofesor) $missing = True;
            }
            if ($idprofesor) {
                if ($fit->User_P_Guia->id_user != $idprofesor) $missing = True;
            }
            $rutFound = 0;
            $dateFound = 0;
            // revision de los usuarios
            if (count($fit->Fit_User) > 0) {
                foreach ($fit->Fit_User as $fitUser) {
                    $user = $fitUser->User;
                    if ($rut) {
                        if ($user->rut == $rut) {
                            $rutFound++;
                        }
                    } else {
                        $rutFound++;
                    }
                    // revision de los datos entre fechas
                    if ($dFechaInicio && $dFechaFin && $user->f_ingreso && $user->f_salida) {
                        $iTime1 = date("Y-m-d",strtotime($dFechaInicio));
                        $iTime2 = date("Y-m-d",strtotime($dFechaFin));
                        $uTime1 = date("Y-m-d",strtotime($user->f_ingreso));
                        $uTime2 = date("Y-m-d",strtotime($user->f_salida));
                        if (($uTime1 >= $iTime1 && $uTime1 <= $iTime2) &&
                            ($uTime2 >= $iTime1 && $uTime2 <= $iTime2))
                            $dateFound++;
                    } elseif (!$dFechaInicio && !$dFechaFin && !$user->f_ingreso && !$user->f_salida) {
                        $dateFound++;
                    } elseif ($user->f_ingreso && $user->f_salida) {
                        $dateFound++;
                    }
                }
                if ($rutFound == 0) $missing = True;
                if ($dateFound == 0) $missing = True;
            }
            if ($missing) {
                unset($fits[$key]);
            }
            $key++;
        }
        return $fits;
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
        $fechaInicio   = ($fechaInicio == NULL) ? ($fechaInicio = '2000-01-01') : Carbon::parse($fechaInicio)->startOfDay();
        $fechaFin      = ($fechaFin == NULL)    ? ($fechaFin = '2100-01-01')    : Carbon::parse($fechaFin)->endOfDay();


        $logs = Log::select('actividad', 'rol', 'ip', 'target', 'logs.created_at as fecha', 'users.nombres', 'users.apellidos')
                   ->join('users', 'logs.user_id', 'users.id_user')
                   ->where('actividad', 'like', "%$actividad%")
                   ->where('user_id', 'like', "%$idUsuario%")
                   ->where('rol', 'like', "%$rol%")
                   ->WhereBetween('logs.created_at', [$fechaInicio, $fechaFin])
                   ->orderByDesc('logs.created_at')->get();

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
