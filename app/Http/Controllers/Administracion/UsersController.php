<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Fit;
use App\User;
use App\Users_Roles;
use App\Users_Permissions;
use App\File;
use App\Roles;
use App\Permission;
use App\Roles_Permissions;
use App\Imports\UsersImport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Debugbar;
use \stdClass;

class UsersController extends Controller
{
    public function getListarUsuarios(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdUsuario   =   $request->nIdUsuario;
        $cNombre      =   $request->cNombre;
        $cApellido    =   $request->cApellido;
        $cCorreo      =   $request->cCorreo;
        $cEstado      =   $request->cEstado;
        $nEscuela     =   $request->nEscuela;
        $nIdRol       =   $request->nIdRol;

        $nIdUsuario = ($nIdUsuario == NULL) ? ($nIdUsuario = 0) : $nIdUsuario;
        $cNombre    = ($cNombre == NULL) ? ($cNombre = '') : $cNombre;
        $cApellido  = ($cApellido == NULL) ? ($cApellido = '') : $cApellido;
        $cCorreo    = ($cCorreo == NULL) ? ($cCorreo = '') : $cCorreo;
        $cEstado    = ($cEstado == NULL) ? ($cEstado = '') : $cEstado;
        $nEscuela   = ($nEscuela == NULL) ? ($nEscuela = '0') : $nEscuela;

        $iduser   = Auth::id();

        $admins = Users_Roles::where('id_roles', '1')->pluck('id_user')->all();

        $rpta = DB  ::table('users')
                    ->leftjoin('files', 'users.id_files', '=', 'files.id')
                    ->leftJoin('users_roles', 'users_roles.id_user', '=', 'users.id_user')
                    ->leftJoin('roles','roles.id','=','users_roles.id_roles')
                    ->leftjoin('escuelas', 'escuelas.id', '=', 'users.id_escuela')
                    ->select('users.*','escuelas.nombre as nombreEscuela')
                    ->selectRaw('CONCAT_WS(" ", nombres, apellidos) as fullname, files.path as profile_image')
                    ->where('nombres', 'like', "%$cNombre%")
                    ->where('apellidos', 'like', "%$cApellido%")
                    ->where('users.email', 'like', "%$cCorreo%")
                    ->where('users.state', 'like', "%$cEstado%")
                    ->where('users.id_user', '<>', "$iduser")
                    ->whereNotIn('users.id_user', $admins);
        if ($nIdRol) $rpta->where('roles.id', "=", "$nIdRol");
        if ($nEscuela != 0) $rpta->where('users.id_escuela',$nEscuela);
        $rpta = $rpta->groupBy('id_user')->orderBy('nombres')->get();
        foreach ($rpta as $data) {
            $data->id_roles = Users_Roles::where('id_user', "$data->id_user")->get()->pluck('id_roles');
            $data->roles = Roles::whereIn('id', $data->id_roles)->get();
        }
        return $rpta;
    }

    public function getListarUserById(Request $request){
        if(!$request->ajax()) return redirect('/');
        $rpta = User::find($request->nIdUsuario);
        $rpta->Escuelas;
        $rpta->File;
        return $rpta;
    }

    public function setRegistrarUsuario(Request $request){
        if(!$request->ajax()) return redirect('/');

        $cNombre        = $request->cNombre;
        $cRut           = $request->cRut;
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

        $chekMail = User::where('email', $cCorreo)->count();
        if ($chekMail) {
            return 0;
        }

        $newUser = User::create([
            'nombres' => $cNombre,
            'apellidos' => $cApellido,
            'email' => $cCorreo,
            'password' => $cContrasena,
            'id_escuela' => $cEscuela,
            'rut' => $cRut
        ]);

        return $newUser->id_user;
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
    public function setEditarUsuarioView(Request $request){
        if(!$request->ajax()) return redirect('/');
        $user = User::find($request->nIdUsuario);
        // COmprobación de usuarios existentes Rut y Correo
        $userCompRut = User::where('rut', $request->cRut)->first();
        $userCompCorreo = User::where('email', $request->cCorreo)->first();
        if ($userCompRut && $user->rut != $request->cRut) return response()->json(['error' => 'El Rut ingresado ya existe.'], 401);
        if ($userCompCorreo && $user->email != $request->cCorreo) return response()->json(['error' => 'El correo ingresado ya existe.'], 401);

        $user->rut          =   $request->cRut;
        $user->nombres      =   $request->cNombre;
        $user->apellidos    =   $request->cApellido;
        $user->id_escuela   =   $request->cEscuela;
        if ($request->idFotografia != 0) {
            $user->id_files     =   $request->idFotografia;
        }
        $user->email        =   $request->cCorreo;
        if ($request->cContrasena) {
            $user->password     =   Hash::make($request->cContrasena);
        }
        $user->birthday     =   $request->f_nacimiento;
        $user->f_ingreso    =   $request->f_entrada;
        $user->f_salida     =   $request->f_salida;
        $user->telefono     =   $request->telefono;
        $user->direccion    =   $request->direccion;
        $user->save();
        return $user;
    }
    public function setEditarDetalleAlumno(Request $request){
        if(!$request->ajax()) return redirect('/');
        $iduser = Auth::id();
        $user = User::find($iduser);
        $user->id_escuela = $request->idEscuela;
        $user->f_ingreso = $request->f_entrada;
        $user->f_salida = $request->f_salida;
        $user->telefono = $request->telefono;
        $user->direccion = $request->direccion;
        $user->grado_academico = 'Estudiante';
        $user->save();
        Auth::login($user);
        return Auth::user();
        // return $user;
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
    public function setEliminarUsuario(Request $request){
        if(!$request->ajax()) return redirect('/');
        $idUser = $request->idUser;
        // se debe registrar si es profesor guia, coguia, si aparece dentro de comision
        $user = User::where('id_user',$idUser)
                    ->get()
                    ->first();
        if (count($user->Fit_User) > 0) return response()->json([
            'mensaje' => 'El usuario posee registros, no puede ser eliminado.',
            'estado' => 'rejected'
        ]);
        if (count($user->P_G_Fit) > 0) return response()->json([
            'mensaje' => 'El usuario es profesor guía de un documento, no puede ser eliminado.',
            'estado' => 'rejected'
        ]);
        if (count($user->P_C_G_Fit) > 0) return response()->json([
            'mensaje' => 'El usuario es profesor co-guía de un documento, no puede ser eliminado.',
            'estado' => 'rejected'
        ]);
        if (count($user->ComisionesP1) > 0 || count($user->ComisionesP2) > 0) return response()->json([
            'mensaje' => 'El usuario es participe de una comisión, no puede ser eliminado.',
            'estado' => 'rejected'
        ]);
        // busqueda de datos de usuario a eliminar
        if (count($user->Users_Roles) > 0) {
            $userRoles = Users_Roles::where('id_user', $user->id_user)->delete();
        }
        if (count($user->Users_Permissions) > 0) {
            $userPermissions = Users_Permissions::where('id_user', $user->id_user)->delete();
        }
        $idFile = $user->id_files;
        User::where('id_user', $user->id_user)->delete();
        if ($idFile) {
            $oldFile = File::where('id',$idFile)->get()->first();
            $oldFileName = last(explode('/', $oldFile->path));
            Storage::delete('public/users/'.$oldFileName);
            File::find($idFile)->delete();
        }
        return response()->json(['mensaje' => 'El usuario ha sido eliminado.', 'estado' => 'Ok'], 200);
    }
    public function setEditarRolByUsuario(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdUsuario = $request->nIdUsuario;
        $nIdRol     = $request->nIdRol;

        $nIdUsuario = ($nIdUsuario == NULL) ? ($nIdUsuario = '') : $nIdUsuario;
        $nIdRol     = ($nIdRol == NULL) ? ($nIdRol = '') : $nIdRol;

        Users_Roles::where('id_user', $nIdUsuario)->delete();

        foreach ($nIdRol as $rol) {
            Users_Roles::create([
                'id_user' => $nIdUsuario,
                'id_roles' => $rol['id']
            ]);
        }
    }
    public function getRolByUsuario(Request $request){
        if(!$request->ajax()) return redirect('/');

        $nIdUsuario = $request->nIdUsuario;

        $nIdUsuario = ($nIdUsuario == NULL) ? ($nIdUsuario = '') : $nIdUsuario;

        $rpta = DB::table('users_roles')
                ->join('roles', 'users_roles.id_roles', 'roles.id')
                ->select('users_roles.id_roles AS id', 'roles.name')
                ->where('id_user', $nIdUsuario)->get()->sortByDesc('id')->values()->all();;

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
        // if(!$request->ajax()) return redirect('/');

        $nIdUsuario = $request->nIdUsuario;
        $rolName = $request->session()->get('rol');
        $rol = Roles::firstWhere('name', $rolName);

        if(!$nIdUsuario){
            $nIdUsuario = Auth::id();
        }

        $permisosUserIds = Users_Permissions::where('id_user', $nIdUsuario)->get()->pluck('id_permission');
        $permisosUser = Permission::whereIn('id', $permisosUserIds)->get();

        $permisosRolUserId = Roles_Permissions::where('id_role', $rol->id)->get()->pluck('id_permission');
        $permisosRolUser = Permission::whereIn('id', $permisosRolUserId)->get();

        $rpta = $permisosUser->union($permisosRolUser);

        return $rpta;
    }

    public function importExcel(Request $request) {
        $import = new UsersImport($request->escuela);
        Debugbar::info($request->escuela);
        $import->import($request->file);
        return $import->errors()->count();
    }

    public function calendarData(Request $request) {
        if(!$request->ajax()) return redirect('/');
        $user = Auth::user();
        $permisos  = $request->permisos;
        $fits = DB  ::table('fit')
                    ->join('escuelas', 'escuelas.id', '=', 'fit.id_escuela')
                    ->leftJoin('fit_user', 'fit_user.id_fit', '=', 'fit.id')
                    ->leftJoin('comisiones', 'comisiones.id_tesis', '=', 'fit.id')
                    ->select('fit.*', 'fit_user.id_user', 'comisiones.id_profesor1', 'comisiones.id_profesor2');
        if (array_search('EsAlumno', $permisos)) {
            $fits = $fits->where('id_user', $user->id_user);
        }
        if (array_search('EsProfesor', $permisos)) {
            $fits = $fits   ->where('id_p_guia', $user->id_user)
                            ->orWhere('id_p_co_guia', $user->id_user)
                            ->orWhere('id_profesor1', $user->id_user)
                            ->orWhere('id_profesor2', $user->id_user);
        }
        if (array_search('fid.acceso.parcial', $permisos)) {
            $fits = $fits->where('id_escuela', $user->id_escuela);
        }
        $fits = $fits->groupBy('id')->get();
        $fits = $fits->pluck('id');
        $data = Fit::whereIn('id', $fits)->get();
        $rpta = [];
        foreach ($data as $fit) {
            if($fit->Fecha_defensa){
                $members = '(';
                foreach ($fit->Fit_User as $fit_user) {
                    if ($fit_user) {
                        $fit_user->User;
                        $members = $members.$fit_user->User->nombres.' '.$fit_user->User->apellidos.', ';
                    }
                }
                $members = substr_replace($members , '', -2, 2);
                $members = $members.') ';
                $newData = new stdClass();
                if ($fit->Fecha_defensa) {
                    $newData->start = $fit->Fecha_defensa->fecha;
                    $newData->sala = $fit->Fecha_defensa->sala;
                }
                if ($fit->titulo) $newData->title = "S: ".$newData->sala.' '.$members.PHP_EOL.$fit->titulo;
                array_push($rpta, $newData);
            }
        }
        return $rpta;
    }
}
