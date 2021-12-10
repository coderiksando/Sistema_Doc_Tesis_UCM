<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Log;
use App\Fit_User;
use App\Fit;
use App\ArchivoPdf;
use Illuminate\Http\Request;
use Debugbar;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function reg($actividad, $target='0', $rol='Sin definir', $id = ''){
        if ($id == '') $id = Auth::id();
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        Log::create([
            'user_id'   => $id,
            'rol'       => $rol,
            'actividad' => $actividad,
            'ip'        => $ip,
            'target'    => $target

        ]);
    }

    function getFit(){
        $idUser    = Auth::id();                          
        $FitUser   = Fit_User::where('id_user', $idUser)->get()->pluck('id_fit');

        $Fit = Fit::whereIn('id', $FitUser)->where('estado', 'D')->where('aprobado_pg', '!=', 'EA')->first();
   
        return $Fit;
    }

    public function getArchivo(Request $request){
        $tipo = $request->tipo;
        $fit = $request->fit;
        $idUser = Auth::user()->id_user;
        if (!$fit) {
            $fit = Fit_User::Firstwhere('id_user', $idUser)->Fit->id;
        }
        $archivoPDF = ArchivoPdf::where('id_fit', $fit);
        if ($tipo == 'constancia_t') {
            $archivoPDF->where('id_propietario', $idUser);
        }

        $archivoPDF = $archivoPDF->orderByDesc('created_at')->firstWhere('tipo_pdf', $tipo);

        return $archivoPDF;
    }

}
