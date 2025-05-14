<?php

namespace App\Console\Commands;

ini_set('memory_limit', '2048M'); // Set memory limit to 2GB

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProcessAbcFiles extends Command
{
    protected $signature = 'process:abcfiles';
    protected $description = 'Process all .abc files and generate a JSON file';

    public function handle()
    {
        $path = base_path('database/abc'); // Path to the folder containing .abc files
        $outputPath = base_path('database/abc_tunes.json'); // Output JSON file path
        $files = File::files($path); // Get all .abc files
        $data = []; // Array to store the JSON structure

        $this->info('Found ' . count($files) . ' .abc files.');

        foreach ($files as $file) {
            $this->info('Processing file: ' . $file->getFilename());
            $filename = $file->getFilename();
            $content = File::get($file);

            if (empty($content)) {
                $this->warn('Skipping empty file: ' . $filename);
                continue;
            }

            $lines = explode("\n", $content);
            $tuneBody = [];
            $foundV1 = false;

            // Loop from the end of the file to find the first occurrence of `V:1`
            for ($i = count($lines) - 1; $i >= 0; $i--) {
                $line = trim($lines[$i]);

                if (strpos($line, 'V:1') === 0) {
                    $foundV1 = true;
                }

                if ($foundV1) {
                    // Collect all lines from the first occurrence of `V:1` to the end of the file
                    $tuneBody = array_slice($lines, $i);
                    break;
                }
            }

            // Skip files without a tune body
            if (!$foundV1) {
                $this->warn('Skipping file without tune body: ' . $filename);
                continue;
            }

            // Convert the collected lines into a string
            $tuneBodyString = implode("\n", $tuneBody);

            // Add the file's data to the JSON structure
            $data[] = [
                'info' => [
                    'filename' => $filename,
                ],
                'variation' => $tuneBodyString,
            ];
        }

        // Save the JSON data to a file
        $this->info('Saving JSON file...');
        File::put($outputPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->info("JSON file generated at: {$outputPath}");
    }
}
