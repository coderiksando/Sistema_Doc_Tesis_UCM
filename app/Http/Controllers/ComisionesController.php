<?php

namespace App\Http\Controllers;

use App\Comisiones;
use App\User;
use App\Fit;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComisionesController extends Controller
{
    public function setRegistrarComision(Request $request){

        if(!$request->ajax()) return redirect('/');

        $idTesis = $request->idTesis;
        $tesis = Fit::find($idTesis);

        if($tesis->Comisiones)
        {
            return response(['error' => 'Comisión ya existe'], 409);
        }
        else{
            $comision = new Comisiones();
            $comision->id_tesis = $idTesis;
            $comision->id_profesor1 = $request->Profesor1;
            $comision->id_profesor2 = $request->Profesor2;
            $comision->p_externo = $request->NombrePEx;
            $comision->correo_p_externo = $request->EmailPEx;
            $comision->institucion_p_externo = $request->InstitucionPEx;
            $comision->save();

            return $comision;
        }
    }

    public function getComision(Request $request){
        if(!$request->ajax()) return redirect('/');
        $IdComision = $request->id;
        $Comision = Comisiones::find($IdComision);
        $Comision->UserP1;
        $Comision->UserP2;
        $Comision->Fit->Fit_User;
        foreach ($Comision->Fit->Fit_User as $fit_user) {
            $fit_user->User;
        }
        return $Comision;
    }
    public function setEditarComision(Request $request){
        if(!$request->ajax()) return redirect('/');

        $id                     = $request->IdComision;
        $comision   = Comisiones::find($id);

        $comision->id_profesor1             = $request->Profesor1;
        $comision->id_profesor2             = $request->Profesor2;
        $comision->p_externo                = $request->NombrePEx;
        $comision->correo_p_externo         = $request->EmailPEx;
        $comision->institucion_p_externo    = $request->InstitucionPEx;
        $comision->update();

        return;
    }
    public function getListarMisComisiones(Request $request){
        if(!$request->ajax()) return redirect('/');
        $IdProfesor  = Auth::id();
        $MisComisiones = Fit::where('id_p_guia', $IdProfesor)->get()->all();
        if ($MisComisiones) {
            foreach ($MisComisiones as $fit) {
                if ($fit->Comisiones) {
                    $fit->Comisiones->UserP1;
                    $fit->Comisiones->UserP2;
                }
                $fit->User_P_Guia;
                $fit->User_P_Coguia;
                $fit->Fit_User;
                if ($fit->Fit_User) {
                    foreach ($fit->Fit_User as $user_comision) {
                        $user_comision->User;
                    }
                }
            }
        }
        return $MisComisiones;
    }
    public function getListarComisiones(Request $request){
        if(!$request->ajax()) return redirect('/');
        $IdProfesor  = Auth::id();
        $ComisionesTotales = [];
        $Comisiones  = User::find($IdProfesor);
        $Comisiones->ComisionesP1;
        if ($Comisiones->ComisionesP1) {
            foreach ($Comisiones->ComisionesP1 as $comision) {
                $comision->Fit;
                $comision->Fit->User_P_Guia;
                $comision->UserP1;
                $comision->UserP2;
                $comision->Fit->Fit_User;
                if ($comision->Fit->Fit_User) {
                    foreach ($comision->Fit->Fit_User as $comision_fit_user) {
                        $comision_fit_user->User;
                    }
                }
                array_push($ComisionesTotales, $comision);
            }
        }
        $Comisiones->ComisionesP2;
        if ($Comisiones->ComisionesP2) {
            foreach ($Comisiones->ComisionesP2 as $comision) {
                $comision->Fit;
                $comision->UserP1;
                $comision->UserP2;
                $comision->Fit->Fit_User;
                if ($comision->Fit->Fit_User) {
                    foreach ($comision->Fit->Fit_User as $comision_fit_user) {
                        $comision_fit_user->User;
                    }
                }
                array_push($ComisionesTotales, $comision);
            }
        }
        return $ComisionesTotales;
    }

}
