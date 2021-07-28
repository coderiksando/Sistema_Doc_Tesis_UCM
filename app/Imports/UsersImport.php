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
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'rut'           => $row['rut'],
            'nombres'       => $row['nombre'],
            'apellidos'     => $row['apellido'],
            'email'         => $row['direccion_de_correo_electronico'],
            'telefono'      => $row['fono'],
            'password'      => Hash::make('12345'),
            'state'         => 'A',
            // 'direccion'     => $row['direccion'],
            // 'f_ingreso'     => Carbon::parse($row['ingreso']),
            // 'f_salida'      => Carbon::parse($row['salida'])
         ]);
    }
}
