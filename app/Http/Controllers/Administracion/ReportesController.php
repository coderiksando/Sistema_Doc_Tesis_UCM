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
use Illuminate\Support\Facades\Auth;
use Debugbar;

class ReportesController extends Controller
{
    public function getListarTesisReporte(Request $request){
        if(!$request->ajax()) return redirect('/');

        $rut                = $request->nRut;
        $idFacultad         = $request->nIdFacultad;
        $idEscuela          = $request->nIdEscuela;
        $estado_notap       = $request->cEstadoNotap;
        $idprofesor         = $request->nIdProfesor;
        $estado             = $request->cEstadoTesis;
        $dFechaInicio       = $request->dFechaInicio;
        $dFechaFin          = $request->dFechaFin;
        $nIdVinculacion     = $request->nIdVinculación;
        $cTipoVinculación   = $request->cTipoVinculación;
        $cTitulo            = $request->cTitulo;
        $nCantAvances0      = $request->nCantAvances0;
        $nCantAvances1      = $request->nCantAvances1;
        $dFechaFIDIni       = $request->dFechaFIDIni;
        $dFechaFIDFin       = $request->dFechaFIDFin;

        $fits = DB  ::table('fit')
                    ->join('escuelas','escuelas.id','=','fit.id_escuela')
                    ->join('facultad','facultad.id','=','escuelas.id_facultad')
                    ->leftJoin('fit_user','fit_user.id_fit','=','fit.id')
                    ->leftJoin('users as alumno','alumno.id_user','=','fit_user.id_user')
                    ->join('users as profesor','profesor.id_user','=','fit.id_p_guia')
                    ->join('vinculaciones','vinculaciones.id','=','fit.id_vinculacion')
                    ->leftJoin('notaspendientes','notaspendientes.id_tesis','=','fit.id')
                    ->select('fit.id');
        if ($rut)               $fits->where('alumno.rut','like',"%$rut%");
        if ($idFacultad)        $fits->where('facultad.id','=',"$idFacultad");
        if ($idEscuela)         $fits->where('fit.id_escuela','=',"$idEscuela");
        if ($estado_notap) {
            if ($estado_notap == 'S') {
                $idNotaP= DB::table('notaspendientes')->select('id_tesis')->get()->pluck('id_tesis');
                $fits->whereNotIn('fit.id', $idNotaP);
            }
            else $fits->where('notaspendientes.estado','=',"$estado_notap");
        }
        if ($idprofesor)        $fits->where('fit.id_p_guia','=',"$idprofesor");
        if ($estado)            $fits->where('fit.estado','=',"$estado");
        if ($dFechaInicio || $dFechaFin)
            $fits   ->whereBetween('alumno.f_ingreso',[$dFechaInicio, $dFechaFin])
                    ->whereBetween('alumno.f_salida',[$dFechaInicio, $dFechaFin]);
        if ($nIdVinculacion)    $fits->where('fit.id_vinculacion','=',"$nIdVinculacion");
        if ($cTipoVinculación)  $fits->where('vinculaciones.tipo','=',"$cTipoVinculación");
        if ($cTitulo)           $fits->where('fit.titulo','like',"%$cTitulo%");
        if ($dFechaFIDIni || $dFechaFIDFin)
            $fits->whereBetween('fit.updated_at',[$dFechaFIDIni,$dFechaFIDFin]);
        if ($nCantAvances0 || $nCantAvances1) {
            $fits   ->join('avancestesis','avancestesis.id_tesis','=','fit.id')
                    ->groupBy('avancestesis.id_tesis')
                    ->havingRaw('COUNT(avancestesis.id_tesis) BETWEEN ? AND ?',[$nCantAvances0, $nCantAvances1]);
            if (!$nCantAvances0) {
                $exist = DB ::table('avancestesis')
                            ->select('id_tesis')
                            ->pluck('id_tesis');
                $missing= DB::table('fit')
                            ->select('id')
                            ->whereNotIn('id', $exist);
                $fits = $fits->union($missing)->orderBy('id');
            }
        }
        $fits = $fits   ->groupBy('id')
                        ->get()
                        ->pluck('id');
        $fits = Fit::whereIn('id', $fits)->get();
        foreach ($fits as $fit) {
            $fit->Bitacoras;
            $fit->AvancesTesis;
            $fit->NotasPendientes;
            $fit->Vinculaciones;
            $fit->Comisiones;
            if ($fit->Comisiones) {
                $fit->Comisiones->UserP1;
                $fit->Comisiones->UserP2;
            }
            $fit->User_P_Guia;
            $fit->User_P_Guia->Escuelas;
            $fit->User_P_Coguia;
            $fit->Escuela;
            $fit->ultimoDoc = $fit->ArchivoPdf()
                ->where(function ($var) {
                    $var->where('tipo_pdf', '=', 'final_t')
                        ->orWhere('tipo_pdf', '=', 'avance_t')
                        ->orWhere('tipo_pdf', '=', 'revision');
                })
                ->get()->sortByDesc('updated_at')->values()->first();
            $fit->acta = $fit->ArchivoPdf()
                ->where('tipo_pdf', '=', 'acta')
                ->get()->first();
            $fit->constancia = $fit->ArchivoPdf()
                ->where('tipo_pdf', '=', 'constancia_t')
                ->get()->first();
            foreach($fit->getAlumnos() as $alumno) {
                $alumno->Escuelas;
                $alumno->Escuelas->Facultad;
            }
        }
        $fits = $fits->sortBy(function ($fit, $key) {
            return $fit->Escuela->nombre.$fit->User_P_Guia->nombres.$fit->User_P_Guia->apellidos;
        })->values()->all();
        return $fits;
    }
    public function getListarTesisHome(Request $request){
        if(!$request->ajax()) return redirect('/');

        $titulo             = $request->cTitulo;
        $idEscuela          = $request->nIdEscuela;
        $idprofesor         = $request->nIdProfesor;

        $titulo             = ($titulo == NULL) ?          ($titulo = '')         : $titulo;
        $idEscuela          = ($idEscuela == NULL) ?    ($idEscuela = '')   : $idEscuela;
        $idprofesor         = ($idprofesor == NULL) ?   ($idprofesor = '')  : $idprofesor;

       $archivosPdf =  ArchivoPdf::select('fit.titulo', 'fit.descripcion', 'fit.updated_at', 'path', 'users.nombres', 'users.apellidos', 'escuelas.nombre', 'escuelas.id', 'users.id_user')
                                ->join('fit', 'id_fit', '=', 'fit.id')
                                ->join('users', 'fit.id_p_guia', '=', 'users.id_user')
                                ->join('escuelas', 'users.id_escuela', '=', 'escuelas.id')
                                ->where('tipo_pdf', 'final_t')
                                ->where('fit.privado','0')
                                ->where('fit.fecha_publicacion','<=',Carbon::now());

        $reportdata = $archivosPdf->where('titulo', 'like', "%$titulo%");
        if ($idprofesor) $reportdata = $reportdata->where('id_user','=', "$idprofesor");
        if ($idEscuela) $reportdata = $reportdata->where('escuelas.id','=', "$idEscuela");
        $reportdata = $reportdata->get();

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

        $logs = Log ::join('users', 'logs.user_id', 'users.id_user')
                    ->where('actividad', 'like', "%$actividad%");
        if ($idUsuario) $logs = $logs->where('user_id', '=', "$idUsuario");
        $logs = $logs   ->where('rol', 'like', "%$rol%")
                        ->WhereBetween('logs.created_at', [$fechaInicio, $fechaFin])
                        ->select('actividad', 'rol', 'ip', 'target', 'logs.created_at as fecha', 'users.nombres', 'users.apellidos')
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
                        ->where('roles.name', 'Profesor');
        if ($escuela) $profesores = $profesores->where('users.id_escuela', '=', "$escuela");
        $profesores = $profesores->orderBy('fullname')->get();
        return $profesores;
    }
}
