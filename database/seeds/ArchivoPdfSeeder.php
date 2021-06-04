<?php

use Illuminate\Database\Seeder;
use App\ArchivoPdf;

class ArchivoPdfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('archivopdf')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        factory(ArchivoPdf::class, 10)->create();
    }
}
