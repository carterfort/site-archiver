<?php

namespace App\Jobs;

use ZipArchive;
use App\Jobs\Job;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompressArchive extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $directory;

    protected $sessionId;

    public function __construct($sessionId, $directory)
    {
        //
        $this->directory = $directory;
        $this->sessionId = $sessionId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get real path for our folder
        $rootPath = storage_path('output/'.$this->directory);

        // Initialize archive object
        $zip = new ZipArchive();
        if ( ! is_dir(storage_path('archives'))){
            mkdir(storage_path('archives') );
        }

        $zip->open(storage_path('archives/'.$this->sessionId.'.zip'), 
                ZipArchive::CREATE | ZipArchive::OVERWRITE
        );

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
            // Get real and relative path for current file
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);

            // Add current file to archive
            $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
    }
}
