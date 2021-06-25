<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Log;
use App\Fit_User;
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
        Debugbar::info($idUser);                           
        $FitUser   = Fit_User::Firstwhere('id_user', $idUser);
        Debugbar::info($FitUser);  
        $Fit = [];

        if ($FitUser) {
            $Fit = $FitUser->Fit;
        }
        
        return $Fit;
        //return response()->json($Fit);
    }

}
