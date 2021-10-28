<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailNotafinal;
use App\User;
use App\Fit;
use App\ArchivoPdf;
use App\Fit_User;
use \stdClass;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Debugbar;

class SecretariaController extends Controller
{
    public function getListarAlumnos(Request $request){
        if(!$request->ajax()) return redirect('/');

        $user     = Auth::user();
        $IdEscuela  = $user->id_escuela;

        $rut            = $request->nRut;
        $nombre         = $request->cNombre;
        $estado         = $request->cEstado_tesis;
        $EstadoAlumno   = $request->nEstadoAlumno;
        $nivelAcceso    = $request->nivelAcceso;

        $rut        = ($rut == NULL) ? ($rut = '') : $rut;
        $nombre     = ($nombre == NULL) ? ($nombre = '') : $nombre;
        $estado     = ($estado == NULL) ? ($estado = '') : $estado;
        $IdEscuela  = ($IdEscuela == NULL) ? ($IdEscuela = '') : $IdEscuela;
        $EstadoAlumno  = ($EstadoAlumno == NULL) ? ($EstadoAlumno = '') : $EstadoAlumno;

        $filter1 = User::where('nombres', 'like', "%$nombre%")->orWhere('apellidos', 'like', "%$nombre%")->get();
        $filter2 = User::where('rut', 'like', "%$rut%");
        if ($nivelAcceso) $filter2->where('id_escuela', $IdEscuela);
        $filter2 = $filter2->get();

        $users = $filter1->intersect($filter2)->pluck('id_user');

        $fitsId = Fit_User::whereIn('id_user', $users)->pluck('id_fit')->unique()->values();
        $fits = Fit::whereIn('id', $fitsId)->where('estado', 'like', "%$EstadoAlumno%")->where('aprobado_pg', 'V')->select('id', 'estado', 'aprobado_pg', 'nota', 'tipo')->get();

        $docs = $fits->map(function ($item, $key) {
            $acta = ArchivoPdf::select('path')->where('id_fit', $item->id)->firstWhere('tipo_pdf', 'acta');
            $constancia = ArchivoPdf::where('id_fit', $item->id)->firstWhere('tipo_pdf', 'constancia_t');
            $final = ArchivoPdf::where('id_fit', $item->id)->firstWhere('tipo_pdf', 'final_t');
            $item->alumnos = $item->getAlumnos();
            $item->constancia = $constancia;
            $item->final = $final;
            return collect($item)->merge($acta);
        });

        return $docs;
    }
    public function setGenerarMemoRevision(Request $request){

        $id        = $request->nIdTesis;
        $logo      = public_path('img/logo_ucm_marca.png');

        $datosmemo = Fit::find($id);
        $alumnos = User::select('nombres', 'apellidos', 'rut')->whereIn('id_user', $datosmemo->Fit_User->pluck('id_user')->all())->get();
        $datosmemo->alumnos = $alumnos->all();
        $fechainicial = Carbon::now();
        $datosmemo->fechainicial = $fechainicial->toFormattedDateString();
        $fechafinal   = Carbon::now();
        $fechafinal   = $fechafinal->addDay(21);
        $datosmemo->fechafinal = $fechafinal->format('d-m-Y');
        $pdf          = PDF::loadView('reportes.tesis.pdf.memorevision',[
                                    'datosmemo' => $datosmemo,
                                    'logo' => $logo,
        ]);
        return $pdf->download('invoice.pdf');
    }
    public function setSubirActa(Request $request){
        if(!$request->ajax()) return redirect('/');

        $id = $request->id_tesis;
        $file = $request->file;
        $bandera = Str::random(10);
        $filename = $file->getClientOriginalName();
        $fileserver = $bandera .'_'. $filename;

        Storage::putFileAs('public/users', $file, $fileserver);

        $rpta = new ArchivoPdf;
        $rpta->path = asset('storage/users/'.$fileserver);
        $rpta->filename = $filename;
        $rpta->id_fit = $id;
        $rpta->tipo_pdf = 'acta';
        $rpta->save();
        $this->reg('Subir Acta', $rpta->path,  $request->session()->get('rol'));

        return $rpta;
    }



    public function setRegistrarNota(Request $request){
        //if(!$request->ajax()) return redirect('/');

        $id         = $request->id_tesis;
        $nota       = $request->nota;

        $DatosEmail = new stdClass();
        $fit        = Fit::find($id);
        $fitUser    = Fit_User::where('id_fit', $fit->id)->get()->pluck('id_user');
        $alumnos    = User::whereIn('id_user', $fitUser)->get();
        $fit->User_P_Guia;

        $DatosEmail->emailpg = $fit->User_P_Guia->email;
        $DatosEmail->titulo = $fit->titulo;
        $DatosEmail->fecha = Carbon::now();
        $DatosEmail->nota = $nota;
        if($nota >= 4 || $nota == 0){
            $estado = 'A';
            $DatosEmail->estadonota = 'Aprobo';
        }else{
            $estado = 'R';
            $DatosEmail->estadonota = 'Reprobo';
        }
        foreach($alumnos as $alumno){
            $DatosEmail->email = $alumno->email;
            $DatosEmail->full_name = $alumno->nombres . ' ' . $alumno->apellidos;
            // Mail::to([$DatosEmail->email, $DatosEmail->emailpg])->queue(new MailNotafinal($DatosEmail));
        }

        Fit::find($id)->update(['nota'=>$nota,'estado'=>$estado]);
        $this->reg('Subir nota', $fit->id,  $request->session()->get('rol'));
    }
}
