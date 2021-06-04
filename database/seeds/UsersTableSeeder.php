<?php

use App\User;
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
            'rut' => '22.222.222-2',
            'nombres'=>'Profesor1',
            'apellidos'=>'Empleado',
            'id_escuela'=>'1',
            'email'=>'eriksandovalsepulveda@gmail.com',
            'password'     =>  Hash::make('12345'),
            'state' => 'A',
            'birthday' => DateTime::createFromFormat('m-d-Y', '12-16-2003'),
            'telefono' => '46986565',
            'direccion' => 'Su casa',
            'grado_academico' => 'Profe'
        ]);
        DB::table('users')->insert([// cuenta de administrador
            'rut' => '33.333.333-3',
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
            'rut' => '44.444.444-4',
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
            'rut' => '18.576.437-0',
            'nombres'=>'Alumno1',
            'apellidos'=>'alumno',
            'id_escuela'=>'1',
            'email'=>'monox_13@hotmail.com',
            'password' => Hash::make('12345'),
            'state' => 'A',
            'birthday' => DateTime::createFromFormat('m-d-Y', '12-16-1803'),
            'telefono' => '65165',
            'direccion' => 'Su casa',
            'grado_academico' => 'Estudiante',
            'f_ingreso' => DateTime::createFromFormat('m-d-Y', '01-01-2015'),
            'f_salida' => DateTime::createFromFormat('m-d-Y', '01-01-2021')
        ]);
        DB::table('users')->insert([// cuenta de administrador
            'rut' => '66.666.666-6',
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
        DB::table('users')->insert([// cuenta de administrador
            'rut' => '55.555.555-5',
            'nombres'=>'Juan',
            'apellidos'=>'alumno',
            'id_escuela'=>'1',
            'email'=>'erik.sandoval@alu.ucm.cl',
            'password' => Hash::make('12345'),
            'state' => 'A',
            'birthday' => DateTime::createFromFormat('m-d-Y', '12-16-1803'),
            'telefono' => '65165',
            'direccion' => 'Su casa',
            'grado_academico' => 'Estudiante',
            'f_ingreso' => DateTime::createFromFormat('m-d-Y', '01-01-2015'),
            'f_salida' => DateTime::createFromFormat('m-d-Y', '01-01-2021')
        ]);

        factory(User::class, 40)->create();
    }
}
