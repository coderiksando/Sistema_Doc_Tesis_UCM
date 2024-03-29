<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailPasswordReset;
use Illuminate\Support\Str;
use App\User;
use App\PassRecovery;
use App\Users_Roles;
use App\Users_Permissions;
use App\Permission;
use App\Roles_Permissions;
use App\Roles;
use Debugbar;


class LoginController extends Controller
{

    public function login(Request $request)
    {
        $cEmail         =   $request->cEmail;
        $cContrasena    =   $request->cContrasena;

        $rpta = Auth::attempt(['email' => $cEmail, 'password' => $cContrasena, 'state' => 'A']);
        if ($rpta) {
            $maxIdRol = Users_Roles::where('id_user', Auth::id())->max('id_roles');
            $rol = Roles::find($maxIdRol);
            $this->reg('login', 0, $rol->name);
            session(['rol' => $rol->name]);
            return response()->json([
                'authUser'  =>  Auth::user(),
                'code'      =>  200
            ]);
        } else {
            return response()->json([
                'code'      =>  401
            ]);
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json([
            'code'      =>  204
        ]);
    }
    public function sendToken(Request $request){

        $user = User::where('email', $request->email)->first();

        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            return response()->json(['error' => 'Ingresa un correo válido'], 401);
        }
        if(!isset($user->id_user)){

            return response()->json(['error' => 'Este correo no esta en nuestros registros.'], 401);
        }
        $token = Str::random(32);

        Mail::to($user)->send(new MailPasswordReset($token));

        $passwordReset = new PassRecovery();
        $passwordReset->email = $user->email;
        $passwordReset->token = $token;
        $passwordReset->save();
    }
    public function validateToken(Request $request){

        $passwordReset = PassRecovery::where('token', $request->token)->first();

        if (!isset($passwordReset->email)){
            return response()->json(['error' => 'Codigo invalido.'], 401);
        }
        // Debugbar::info($passwordReset->email);
        $user = User::where('email', $passwordReset->email)->first();
        // Debugbar::info($user);
        return response()->json($user, 200);

    }
    public function resetPassword(Request $request){
        if(!$request->ajax()) return redirect('/');
        //$user = user::select('email')->where('id_user', $request->id_user)->get();
        $user = User::find($request->id_user);
        $passwordReset = PassRecovery::where('email', $user->email)->get()->all();
        foreach ($passwordReset as $pass) {
            $pass->delete();
        }
        // Debugbar::info($passwordReset);

        $user->password = bcrypt($request->password);
        $user->save();
        //return response()->json($user);
    }
    public function getMyOwnUser(){
        $rpta = User::where('id_user', Auth::user()->id_user)->first();
        foreach ($rpta->Users_Roles->all() as $user_role) {
            $user_role->Roles->first();
        }
        return $rpta;
    }

    public function changeRol(Request $request){
        session(['rol' => $request->rol]);
        $rolName = $request->rol;
        $rol = Roles::firstWhere('name', $rolName);

        $nIdUsuario = Auth::id();
        $permisosUserIds = Users_Permissions::where('id_user', $nIdUsuario)->get()->pluck('id_permission');
        $permisosUser = Permission::whereIn('id', $permisosUserIds);

        $permisosRolUserId = Roles_Permissions::where('id_role', $rol->id)->get()->pluck('id_permission');
        $permisosRolUser = Permission::whereIn('id', $permisosRolUserId);

        $permisos = $permisosUser->union($permisosRolUser)->get();


        return ['rol' => $request->rol,
                'code' => 200,
                'permisos' => $permisos->pluck('slug')
                ];
    }
}

