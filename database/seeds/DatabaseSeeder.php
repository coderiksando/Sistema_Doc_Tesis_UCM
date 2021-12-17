<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([FacultadSeeder::class]);
        $this->call([EscuelasTableSeeder::class]);
        $this->call([UsersTableSeeder::class]);
        $this->call([PermissionsTableSeeder::class]);
        $this->call([RolesSeeder::class]);
        $this->call([RolePermissionSeeder::class]);
        $this->call([UserRoleSeeder::class]);
        $this->call([RolePermissionAlumnoSeeder::class]);
        $this->call([RolePermissionCoordinadorSeeder::class]);
        $this->call([RolePermissionProfesorSeeder::class]);
        $this->call([RolePermissionDirectorSeeder::class]);
        $this->call([RolePermissionSecretariaSeeder::class]);
        $this->call([AreasTesisTableSeeder::class]);
        $this->call([VinculacionesTableSeeder::class]);
        $this->call(FitSeeder::class);
        $this->call(FitUserSeeder::class);
        $this->call(BitacorasSeeder::class);
        $this->call(ArchivoPdfSeeder::class);
        $this->call(AvancesTesisSeeder::class);
        $this->call(ParametrosSeeder::class);
        
    }
}
