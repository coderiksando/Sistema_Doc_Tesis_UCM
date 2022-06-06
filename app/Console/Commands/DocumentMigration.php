<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ArchivoPdf;
use App\File;
use App\DocumentosEscuela;

class DocumentMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:renewpath {ip}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $newIP = $this->argument('ip');
        $documents = ArchivoPDF::all();

        foreach ($documents as $document) {
            $newRoute = explode('/storage/users/', $document->path);
            $newRoute[0] = 'http://' . $newIP;
            $document->path = implode('/storage/users/', $newRoute);
            $document->save();
        }

        $files = File::all();

        foreach ($files as $file) {
            $newRoute = explode('/storage/users/', $file->path);
            $newRoute[0] = 'http://' . $newIP;
            $file->path = implode('/storage/users/', $newRoute);
            $file->save();
        }

        $docEscuela = DocumentosEscuela::all();

        foreach ($docEscuela as $docEsc) {
            if ( $docEsc->link == 0 ) {
                $newRoute = explode('/storage/users/', $docEsc->path);
                $newRoute[0] = 'http://' . $newIP;
                $docEsc->path = implode('/storage/users/', $newRoute);
                $docEsc->save();
            }
        }


    }
}
