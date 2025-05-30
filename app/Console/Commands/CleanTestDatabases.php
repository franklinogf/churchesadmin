<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

final class CleanTestDatabases extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cleanup {db-prefix=test_churchesadmin : The prefix for the test databases}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all parallel test databases';

    /**
     * Execute the console command.
     */
    public function handle(): ?int
    {
        if (app()->isProduction()) {
            $this->error('This command should not be run in production.');

            return 1;
        }

        /**
         * @var string $dbPrefix
         */
        $dbPrefix = $this->argument('db-prefix');

        // Find all SQLite database files in the database directory
        // and delete those that start with 'test_churchesadmin'.

        $this->info('Cleaning up test databases...');
        /**
         * @var array<int,string> $files
         */
        $files = File::glob(database_path("{$dbPrefix}*"));

        foreach ($files as $file) {
            $basename = basename($file);
            if (Str::startsWith($basename, $dbPrefix) && $basename !== "{$dbPrefix}.sqlite") {
                File::delete($file);
                $this->info("Deleted: $file");
            }
        }

        // Find all MySQL databases that start with the given prefix
        $databases = DB::select("SHOW DATABASES LIKE '{$dbPrefix}%'");
        foreach ($databases as $db) {
            /**
             * @var string $dbName
             */
            $dbName = $db->{"Database ({$dbPrefix}%)"};
            if ($dbName === $dbPrefix) {
                continue;
            }
            DB::statement("DROP DATABASE `$dbName`");
            $this->info("Dropped database: $dbName");
        }

        $this->info("Deleting storage folders for databases with prefix: $dbPrefix");
        // delete storage folder for the tenant
        /**
         * @var array<int,string> $storageFolders
         */
        $storageFolders = File::glob(pattern: storage_path("{$dbPrefix}*"));

        foreach ($storageFolders as $folder) {
            File::deleteDirectory($folder);
        }
        /**
         * @var array<int,string> $publicFolders
         */
        $publicFolders = File::glob(pattern: public_path('public-test_*'));

        foreach ($publicFolders as $folder) {
            rmdir($folder);

            $this->info("Deleted: $folder");
        }

        $this->info('Cleanup complete.');

        return null;
    }
}
