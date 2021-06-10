<?php

namespace App\Http\Controllers;

use App\ArchivoPdf;
use App\Fit_User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Debugbar;

class FilesController extends Controller
{
    public function setRegistrarArchivo(Request $request){
        $file = $request->file;
        $bandera = Str::random(10);
        $filename = $file->getClientOriginalName();
        $fileserver = $bandera .'_'. $filename;

        Storage::putFileAs('public/users', $file, $fileserver);

        $rpta = DB::select('call sp_Usuario_setRegistrarArchivo (?, ?)',
                                                                [
                                                                    asset('storage/users/'.$fileserver),
                                                                    $filename                                               
                                                                ]);
        return $rpta;
    }
    public function setRegistrarArchivoPDF(Request $request){
        
        $idUser = Auth::user()->id_user;
        $fit = Fit_User::Firstwhere('id_user', $idUser)->Fit;
        $file = $request->file;
        $bandera = Str::random(10);
        $filename = $file->getClientOriginalName();
        $fileserver = $bandera .'_'. $filename;
        $tipo = $request->tipo;

        Storage::putFileAs('public/users', $file, $fileserver);

        if ($tipo == 'final_t'){
            $lastVersion = $this->getPdfFinal();
            if($lastVersion){
                $name = last(explode('/', $lastVersion->path));
                Storage::delete('public/users/'.$name);
                $lastVersion->delete();
            } 
        }

        $rpta = new ArchivoPdf;
        $rpta->path = asset('storage/users/'.$fileserver);
        $rpta->filename = $filename;
        $rpta->id_fit = $fit->id;
        $rpta->tipo_pdf = $tipo;
        $rpta->save(); 
        //$nIdFile = $rpta->id;                                                               
        return $rpta;
    }

    public function getPdfFinal(){
        $idUser = Auth::user()->id_user;
        $fit = Fit_User::Firstwhere('id_user', $idUser)->Fit;
        $archivoPDF = ArchivoPdf::where('id_fit', $fit->id)->orderByDesc('created_at')->firstWhere('tipo_pdf', 'final_t');
        Debugbar::info(last(explode('/', $archivoPDF->path)));
        return $archivoPDF;
    }

}
