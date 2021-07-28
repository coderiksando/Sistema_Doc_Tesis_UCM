<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Debugbar;


class UsersImport implements ToModel, WithHeadingRow, SkipsOnError 
{
    use Importable, SkipsErrors;

    private $escuela;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function __construct($escuela) {
        $this->escuela = $escuela;
     }

    public function model(array $row)
    {
        return new User([
            'rut'           => $this->formatRut($row['rut']),
            'nombres'       => $row['nombre'],
            'apellidos'     => $row['apellido'],
            'email'         => $row['direccion_de_correo_electronico'],
            'telefono'      => $row['fono'],
            'password'      => Hash::make('12345'),
            'state'         => 'A',
            'id_escuela'    => $this->escuela,
            // 'direccion'     => $row['direccion'],
            // 'f_ingreso'     => Carbon::parse($row['ingreso']),
            // 'f_salida'      => Carbon::parse($row['salida'])
         ]);
    }

    public function formatRut($rut){
        $rut = str_replace ( ".", '', $rut);
        $rut = str_replace ( " ", '', $rut);
        return $rut;
    }
}
