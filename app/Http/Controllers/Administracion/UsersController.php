<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function getListarUsuarios(Request $request){
        if(!$request->ajax()) return redirect('/');

        
        $nIdUsuario   =   $request->nIdUsuario;
        $cNombre      =   $request->cNombre;
        $cApellido    =   $request->cApellido;
        $cCorreo      =   $request->cCorreo;
        $cEstado      =   $request->cEstado;
        $cEscuela     =   $request->cEscuela;
        
        $nIdUsuario = ($nIdUsuario == NULL) ? ($nIdUsuario = 0) : $nIdUsuario;
        $cNombre    = ($cNombre == NULL) ? ($cNombre = '') : $cNombre;
        $cApellido  = ($cApellido == NULL) ? ($cApellido = '') : $cApellido;
        $cCorreo    = ($cCorreo == NULL) ? ($cCorreo = '') : $cCorreo;
        $cEstado    = ($cEstado == NULL) ? ($cEstado = '') : $cEstado;
        $cEscuela   = ($cEscuela == NULL) ? ($cEscuela = 0) : $cEscuela;

        $iduser   = Auth::id();

        $rpta = DB::select('call sp_Usuario_getListarUsuarios (?, ?, ?, ?, ?, ?)',
                                                                [
                                                                    $iduser,
                                                                    $cNombre,
                                                                    $cApellido,
                                                                    $cCorreo,
                                                                    $cEstado,
                                                                    $cEscuela
                                                                ]);

        return $rpta;
    }

    public function getListarUserById(Request $request){
        if(!$request->ajax()) return redirect('/');
        
        
        $rpta = DB::table('users')->where('id_user', $request->nIdUsuario)->first();
        //var_dump($rpta);
        
        return response()->json([$rpta]);
    }
    
    public function setRegistrarUsuario(Request $request){
        if(!$request->ajax()) return redirect('/');

        $cNombre        = $request->cNombre;
        $cApellido      = $request->cApellido;
        $cCorreo        = $request->cCorreo;
        $cContrasena    = Hash::make($request->cContrasena);
        $cEscuela       = $request->cEscuela;
        $oFotografia    = $request->oFotografia;

        $cNombre        = ($cNombre == NULL) ? ($cNombre = '') : $cNombre;
        $cApellido      = ($cApellido == NULL) ? ($cApellido = '') : $cApellido;
        $cCorreo        = ($cCorreo == NULL) ? ($cCorreo = '') : $cCorreo;
        $cContrasena    = ($cContrasena == NULL) ? ($cContrasena = '') : $cContrasena;
        $cEscuela       = ($cEscuela == NULL) ? ($cEscuela = '') : $cEscuela;
        $oFotografia    = ($oFotografia == NULL) ? ($oFotografia = NULL) : $oFotografia;

        $rpta = DB::select('call sp_Usuario_setRegistrarUsuario (?, ?, ?, ?, ?, ?)',
                                                                [
                                                                    $cNombre,
                                                                    $cApellido,
                                                                    $cCorreo,
                                                                    $cContrasena,
                                                                    $cEscuela,
                                                                    $oFotografia
                                                                ]);
        return $rpta[0]->nIdUsuario;
    }
    //     EDITAR USUARIOS
    public function setEditarUsuario(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdUsuario     = $request->nIdUsuario;
        $cNombre        = $request->cNombre;
        $cApellido      = $request->cApellido;
        $cCorreo        = $request->cCorreo;
        $cContrasena    = $request->cContrasena;

        var_dump($cContrasena);
        if($cContrasena != NULL){
                $cContrasena = Hash::make($cContrasena);
        }
        var_dump($cContrasena);
        $cEscuela       = $request->cEscuela;
        $oFotografia    = $request->oFotografia;

        $nIdUsuario     = ($nIdUsuario == NULL) ? ($nIdUsuario = '') : $nIdUsuario;
        $cNombre        = ($cNombre == NULL) ? ($cNombre = '') : $cNombre;
        $cApellido      = ($cApellido == NULL) ? ($cApellido = '') : $cApellido;
        $cCorreo        = ($cCorreo == NULL) ? ($cCorreo = '') : $cCorreo;
        $cContrasena    = ($cContrasena == NULL) ? ($cContrasena = '') : $cContrasena;
        $cEscuela       = ($cEscuela == NULL) ? ($cEscuela = '') : $cEscuela;
        $oFotografia    = ($oFotografia == 0) ? ($oFotografia = 0) : $oFotografia;
        var_dump($cContrasena);
        $rpta = DB::select('call sp_Usuario_setEditarUsuario (?, ?, ?, ?, ?, ?, ?)',
                                                                [
                                                                    $nIdUsuario,
                                                                    $cNombre,
                                                                    $cApellido,
                                                                    $cCorreo,
                                                                    $cContrasena,
                                                                    $cEscuela,
                                                                    $oFotografia
                                                                ]);
    }
    public function setCambiarEstadoUsuario(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdUsuario = $request->nIdUsuario;
        $cEstado    = $request->cEstado;

        $nIdUsuario = ($nIdUsuario == NULL) ? ($nIdUsuario = 0) : $nIdUsuario;
        $cEstado    = ($cEstado == NULL) ? ($cEstado = 0) : $cEstado;

        $rpta = DB::select('call sp_Usuario_setCambiarEstadoUsuario (?, ?)',
                                                                [
                                                                    $nIdUsuario,
                                                                    $cEstado
                                                                ]);
    }
    public function setEditarRolByUsuario(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdUsuario = $request->nIdUsuario;
        $nIdRol     = $request->nIdRol;
       
        $nIdUsuario = ($nIdUsuario == NULL) ? ($nIdUsuario = '') : $nIdUsuario;
        $nIdRol     = ($nIdRol == NULL) ? ($nIdRol = '') : $nIdRol;
       
        $rpta = DB::select('call sp_Usuario_setEditarRolByUsuario (?, ?)',
                                                                [
                                                                    $nIdUsuario,
                                                                    $nIdRol
                                                                ]);
    }
    public function getRolByUsuario(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdUsuario = $request->nIdUsuario;
       
        $nIdUsuario = ($nIdUsuario == NULL) ? ($nIdUsuario = '') : $nIdUsuario;
       
        $rpta = DB::select('call sp_Usuario_getRolByUsuario (?)',
                                                                [
                                                                    $nIdUsuario
                                                                ]);
        return $rpta;
    }
    public function getListarPermisosByRolAsignado(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdUsuario = $request->nIdUsuario;
       
        $nIdUsuario = ($nIdUsuario == NULL) ? ($nIdUsuario = '') : $nIdUsuario;
       
        $rpta = DB::select('call sp_Usuario_getListarPermisosByRolAsignado (?)',
                                                                [
                                                                    $nIdUsuario
                                                                ]);
        return $rpta;
    }
    public function getListarPermisosByUsuario(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdUsuario = $request->nIdUsuario;
       
        $nIdUsuario = ($nIdUsuario == NULL) ? ($nIdUsuario = 0) : $nIdUsuario;
       
        $rpta = DB::select('call sp_Usuario_getListarPermisosByUsuario (?)',
                                                                [
                                                                    $nIdUsuario
                                                                ]);
        return $rpta;
    }
    public function setRegistrarPermisosByUsuario(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdUsuario = $request->nIdUsuario;
       
        $nIdUsuario = ($nIdUsuario == NULL) ? ($nIdUsuario = 0) : $nIdUsuario;

        try{
            DB::beginTransaction();

            DB::select('call sp_Usuario_setEliminarPermisosByUsuario (?)',
                                                                [
                                                                    $nIdUsuario
                                                                ]);
            $listPermisos     = $request->listPermisosFilter;
            $listPermisosSize = sizeof($listPermisos);
            if($listPermisosSize > 0){
                foreach ($listPermisos as $key => $value){
                    if($value['checked'] == true) {
                        DB::select('call sp_Usuario_setRegistrarPermisosByUsuario (?, ?)',
                                                                    [
                                                                        $nIdUsuario,
                                                                        $value['id']
                                                                    ]);
                    }
                }
            }
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
    }
    public function getListarRolPermisosByUsuario(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdUsuario = $request->nIdUsuario;

        if(!$nIdUsuario){
            $nIdUsuario = Auth::id();
        }
       
        $nIdUsuario = ($nIdUsuario == NULL) ? ($nIdUsuario = 0) : $nIdUsuario;
       
        $rpta = DB::select('call sp_Usuario_getListarRolPermisosByUsuario (?)',
                                                                [
                                                                    $nIdUsuario
                                                                ]);
        return $rpta;
    }
}
