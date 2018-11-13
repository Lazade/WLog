<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:cloudbackup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database to the cloud.';

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
     * @return mixed
     */
    public function handle()
    {
        $now      = Carbon::now();
        $database = config('database.connections.mysql.database');
        $subfix   = '_' . $now->format('Y-m-d-H-i-s') . '.sql';
        $filename = $database . $subfix;
        $message = $now->format('Y-m-d-H-i-s') . ': Database auto backup succeed. File name is ' . $filename;
        // database, destination, destinationPath, compression
        $this->call('db:backup', [
            '--database' => 'mysql',
            '--destination' => 'dropbox_db',
            '--destinationPath' => $filename,
            '--compression' => 'gzip',
        ]);

        $this->call('telegramsend', [
            'message' => $message,
        ]);
    }
}
