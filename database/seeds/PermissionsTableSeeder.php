<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        DB::table('permissions')->insert([
            'name' => 'Dashboard',
            'slug' => 'dashboard.index'
        ]);
        //Semillas modulo administracion de usuarios
        DB::table('permissions')->insert([
            'name' => 'Navegar Usuarios',
            'slug' => 'usuarios.index'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Crear Usuarios',
            'slug' => 'usuarios.crear'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Editar Usuarios',
            'slug' => 'usuarios.editar'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Ver Usuarios',
            'slug' => 'usuarios.ver'
        ]);
        //Semillas modulo administracion de roles y permisos
        DB::table('permissions')->insert([
            'name' => 'Permisos Usuarios',
            'slug' => 'usuarios.permisos'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Navegar Roles',
            'slug' => 'roles.index'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Crear Roles',
            'slug' => 'roles.crear'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Editar Roles',
            'slug' => 'roles.editar'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Navegar Permisos',
            'slug' => 'permisos.index'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Crear Permisos',
            'slug' => 'permisos.crear'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Editar Permisos',
            'slug' => 'permisos.editar'
        ]);
       //semillas modulo administracion de Escuelas
        DB::table('permissions')->insert([
        'name' => 'Navegar Escuelas',
        'slug' => 'escuelas.index'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Crear Escuelas',
            'slug' => 'escuelas.crear'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Editar Permisos',
            'slug' => 'escuelas.editar'
        ]);
        //semillas modulo administracion de Areas de tesis
        DB::table('permissions')->insert([
        'name' => 'Navegar Temas de tesis',
        'slug' => 'areatesis.index'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Crear Temas de tesis',
            'slug' => 'areatesis.crear'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Editar Temas de tesis',
            'slug' => 'areatesis.editar'
        ]);
         //semillas modulo administracion de Vinculaciones
        DB::table('permissions')->insert([
            'name' => 'Navegar Vinculaciones',
            'slug' => 'vinculacion.index'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Crear Vinculacion',
            'slug' => 'vinculacion.crear'
        ]);
        DB::table('permissions')->insert([
        'name' => 'Editar Vinculacion',
        'slug' => 'vinculacion.editar'
        ]);
        //semillas modulo administracion formulario de inscripcion de tesis
        DB::table('permissions')->insert([
        'name' => 'Sitios de interes',
        'slug' => 'sitios.interes'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Formulario de inscripcion',
            'slug' => 'tesis.fit'
        ]);
        DB::table('permissions')->insert([
            'name' => 'crear tesis',
            'slug' => 'tesis.crear'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Ver FIT',
            'slug' => 'tesis.ver'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Editar FIT',
            'slug' => 'tesis.editar'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Crear reporte FIT',
            'slug' => 'reportes.reportefit'
        ]);
        //Semillas modulos de avances de tesis
        DB::table('permissions')->insert([
            'name' => 'Navegar Avances',
            'slug' => 'avances.index'
        ]);
        DB::table('permissions')->insert([
            'name' => 'Crear Avances',
            'slug' => 'avances.crear'
        ]);
        DB::table('permissions')->insert([                  //30
            'name' => 'Editar Avances',
            'slug' => 'avances.editar'
        ]);
        //Semillas Botones y funcionas exclusivas 
        DB::table('permissions')->insert([                  //31
            'name' => 'Aprobar Fit',
            'slug' => 'tesis.aprobar'
        ]);
        DB::table('permissions')->insert([                  //32
            'name' => 'Avances por usuario',
            'slug' => 'avances.listaralumnos'
        ]);  
        //Semillas administracion de notas pendientes
        DB::table('permissions')->insert([                  //33
            'name' => 'Navegar notas pendientes',
            'slug' => 'notaspendientes.index'
        ]);
        DB::table('permissions')->insert([                  //34
            'name' => 'Ingresar nota pendiente',
            'slug' => 'notaspendientes.crear'
        ]);
        DB::table('permissions')->insert([                  //35
            'name' => 'Editar nota pendiente',
            'slug' => 'notaspendientes.editar'
        ]);
        DB::table('permissions')->insert([                  //36
            'name' => 'Ingresar Prorrogas',
            'slug' => 'notaspendientes.prorroga'
        ]);    
        //Semillas administracion de notas pendientes  
        DB::table('permissions')->insert([                  //37
            'name' => 'Navegar Bitacoras',
            'slug' => 'bitacoras.index'
        ]); 
        DB::table('permissions')->insert([                  //38
            'name' => 'Crear Bitacoras',
            'slug' => 'bitacoras.crear'
        ]); 
        DB::table('permissions')->insert([                  //39
            'name' => 'Editar Bitacoras',
            'slug' => 'bitacoras.editar'
        ]);
        //Semillas administracion de Comisiones
        DB::table('permissions')->insert([                  //40
            'name' => 'Navegar Comisiones',
            'slug' => 'comisiones.index'
        ]); 
        DB::table('permissions')->insert([                  //41
            'name' => 'Crear Comisiones',
            'slug' => 'comisiones.crear'
        ]); 
        DB::table('permissions')->insert([                  //42
            'name' => 'Editar Comisiones',
            'slug' => 'comisiones.editar'
        ]);
        //Semillas administracion documentos secretaria 
        DB::table('permissions')->insert([                  //43
            'name' => 'Navegar documentos alumnos',
            'slug' => 'actadefensa.index'
        ]); 
        DB::table('permissions')->insert([                  //44
            'name' => 'Subir acta de defensa',
            'slug' => 'actadefensa.subiracta'
        ]); 
        DB::table('permissions')->insert([                  //45
            'name' => 'Subir nota de tesis',
            'slug' => 'actadefensa.subirnota'
        ]); 
        DB::table('permissions')->insert([                  //46
            'name' => 'Documentos alumnos',
            'slug' => 'documentos.index'
        ]); 
        DB::table('permissions')->insert([                  //47
            'name' => 'EsAlumno',
            'slug' => 'EsAlumno'
        ]);
        DB::table('permissions')->insert([                  //48
            'name' => 'EsProfesor',
            'slug' => 'EsProfesor'
        ]);
        DB::table('permissions')->insert([                  //49
            'name' => 'Detalles avances alumno',
            'slug' => 'reportes.detallesavances'
        ]);
        DB::table('permissions')->insert([                  //50
            'name' => 'Detalles avances alumno',
            'slug' => 'reportes.detallesbitacoras'
        ]);
        DB::table('permissions')->insert([                  //51
            'name' => 'Subir version final de tesis',
            'slug' => 'avances.subirfinalpdf'
        ]);
        DB::table('permissions')->insert([                  //52
            'name' => 'Registrar tesis finalizada',
            'slug' => 'registrar.tesis'
        ]);
        DB::table('permissions')->insert([                  //53
            'name' => 'Parametros',
            'slug' => 'parametros.index'
        ]);
        DB::table('permissions')->insert([                  //54
            'name' => 'Constancia',
            'slug' => 'tesis.subirconstancia'
        ]);
        DB::table('permissions')->insert([                  //55
            'name' => 'Ver tabla de registros',
            'slug' => 'reportes.logs'
        ]);
        DB::table('permissions')->insert([                  //56
            'name' => 'Crear Facultad',
            'slug' => 'escuelas.crearfacultad'
        ]);
        DB::table('permissions')->insert([                  //57
            'name' => 'Index de tesis finalizadas',
            'slug' => 'index.tesisfinal'
        ]);
        DB::table('permissions')->insert([                  //58
            'name' => 'Edicion de tesis finalizadas',
            'slug' => 'editar.tesisfinal'
        ]);
        DB::table('permissions')->insert([                  //59
            'name' => 'Importar Usuario',
            'slug' => 'usuarios.importar'
        ]);
        DB::table('permissions')->insert([                  //60
            'name' => 'Ver revisiones de comision',
            'slug' => 'comision.verRevision'
        ]);
        DB::table('permissions')->insert([                  //61
            'name' => 'Ver documentos de escuela',
            'slug' => 'escuelas.documentos.navegar'
        ]);
        DB::table('permissions')->insert([                  //62
            'name' => 'Crear documento de escuela',
            'slug' => 'escuelas.documentos.crear'
        ]);
        DB::table('permissions')->insert([                  //63
            'name' => 'Crear documentos generales',
            'slug' => 'escuelas.documentos.general'
        ]);
        DB::table('permissions')->insert([                  //64
            'name' => 'Ver reportes de todas las escuelas',
            'slug' => 'reportes.general'
        ]);
        DB::table('permissions')->insert([                  //65
            'name' => 'Ver FIDs de todas las escuelas',
            'slug' => 'fid.acceso.total'
        ]);
        DB::table('permissions')->insert([                  //66
            'name' => 'Ver FIDs de su escuela',
            'slug' => 'fid.acceso.parcial'
        ]);
        DB::table('permissions')->insert([                  //67
            'name' => 'Marcar trabajos en abandono',
            'slug' => 'abandono.index'
        ]);
        DB::table('permissions')->insert([                  //68
            'name' => 'Editar información de escuela',
            'slug' => 'escuelas.documentos.editar'
        ]);
        DB::table('permissions')->insert([                  //69
            'name' => 'Registro de documento externo',
            'slug' => 'actadefensa.registroDocumento'
        ]);
    }
}

