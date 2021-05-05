<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('users')->insert([// cuenta de administrador
            'rut' => '11.111.111-1',
            'nombres'=>'Administrador',
            'apellidos'=>'Emperador',
            'id_escuela'=>'1',
            'email'=>'admin@gmail.com',
            'password'     =>  Hash::make('12345'),
            'state' => 'A',
            'birthday' => DateTime::createFromFormat('m-d-Y', '10-16-2003'),
            'telefono' => '4646565',
            'direccion' => 'Su casa',
            'grado_academico' => 'Admin'
        ]);
        DB::table('users')->insert([// cuenta de administrador
            'rut' => '11.111.111-1',
            'nombres'=>'Profesor1',
            'apellidos'=>'Empleado',
            'id_escuela'=>'1',
            'email'=>'profesor1@gmail.com',
            'password'     =>  Hash::make('12345'),
            'state' => 'A',
            'birthday' => DateTime::createFromFormat('m-d-Y', '12-16-2003'),
            'telefono' => '46986565',
            'direccion' => 'Su casa',
            'grado_academico' => 'Profe'
        ]);
        DB::table('users')->insert([// cuenta de administrador
            'rut' => '11.111.111-1',
            'nombres'=>'Director',
            'apellidos'=>'Jefe',
            'id_escuela'=>'1',
            'email'=>'director@gmail.com',
            'password'     =>  Hash::make('12345'),
            'state' => 'A',
            'birthday' => DateTime::createFromFormat('m-d-Y', '12-16-1003'),
            'telefono' => '46986565',
            'direccion' => 'Su casa',
            'grado_academico' => 'Direc'
        ]);
        DB::table('users')->insert([// cuenta de administrador
            'rut' => '11.111.111-1',
            'nombres'=>'Coordinador',
            'apellidos'=>'cordinitas',
            'id_escuela'=>'1',
            'email'=>'coordinador@gmail.com',
            'password' => Hash::make('12345'),
            'state' => 'A',
            'birthday' => DateTime::createFromFormat('m-d-Y', '12-16-1003'),
            'telefono' => '46986565',
            'direccion' => 'Su casa',
            'grado_academico' => 'Coordinador'
        ]);
        DB::table('users')->insert([// cuenta de administrador
            'rut' => '11.111.111-1',
            'nombres'=>'Alumno1',
            'apellidos'=>'estudiador',
            'id_escuela'=>'1',
            'email'=>'alumno1@gmail.com',
            'password' => Hash::make('12345'),
            'state' => 'A',
            'birthday' => DateTime::createFromFormat('m-d-Y', '12-16-1803'),
            'telefono' => '65165',
            'direccion' => 'Su casa',
            'grado_academico' => 'Estudiante'
        ]);
        DB::table('users')->insert([// cuenta de administrador
            'rut' => '11.111.111-1',
            'nombres'=>'Secretaria',
            'apellidos'=>'asist',
            'id_escuela'=>'1',
            'email'=>'secretaria@gmail.com',
            'password' => Hash::make('12345'),
            'state' => 'A',
            'birthday' => DateTime::createFromFormat('m-d-Y', '12-16-1703'),
            'telefono' => '65165',
            'direccion' => 'Su casa',
            'grado_academico' => 'secre'
        ]);
    }
}
