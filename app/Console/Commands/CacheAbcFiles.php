<?php
// filepath: /Users/baobach/Projects/abc_search/app/Console/Commands/CacheAbcFiles.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class CacheAbcFiles extends Command
{
    protected $signature = 'cache:abcfiles';
    protected $description = 'Cache the content of ABC files in batches';

    public function handle()
    {
        $path = base_path('database/abc'); // Path to the folder containing .abc files
        $files = File::files($path); // Get all files in the folder
        $batchSize = 50; // Number of files to process in each batch
        $totalFiles = count($files);
        $batches = array_chunk($files, $batchSize);

        foreach ($batches as $index => $batch) {
            foreach ($batch as $file) {
                $content = strtolower(File::get($file)); // Normalize content
                Cache::put('abc_file_' . $file->getFilename(), $content, 3600); // Cache content
            }

            $this->info('Processed batch ' . ($index + 1) . ' of ' . count($batches));
        }

        $this->info('All ABC files have been cached successfully.');
    }
}
