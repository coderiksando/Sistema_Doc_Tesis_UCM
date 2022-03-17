<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ArchivoPdf;
use App\File;

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
            $newRoute = explode('/', $document->path);
            $newRoute[2] = $newIP;
            $document->path = implode('/', $newRoute);
            $document->save(); 
        }

        $files = File::all();

        foreach ($files as $file) {
            $newRoute = explode('/', $file->path);
            $newRoute[2] = $newIP;
            $file->path = implode('/', $newRoute);
            $file->save(); 
        }

  

    }
}
