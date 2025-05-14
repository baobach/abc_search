<?php

namespace App\Console\Commands;

use App\Models\AbcFile; // Import the model
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log; // Optional: For logging errors
use Exception; // Import Exception class

class ImportAbcFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:abc-files'; // Command signature used in terminal

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import ABC file metadata from the train_dataset.csv file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $csvPath = '/Users/baobach/Projects/abc_search/train_dataset.csv'; // Path to your CSV file
        $this->info("Starting import from: {$csvPath}");

        if (!file_exists($csvPath) || !is_readable($csvPath)) {
            $this->error("CSV file does not exist or is not readable: {$csvPath}");
            return 1; // Return error code
        }

        $header = null;
        $rowCount = 0;
        $importedCount = 0;

        if (($handle = fopen($csvPath, 'r')) !== false) {
            try {
                while (($row = fgetcsv($handle)) !== false) {
                    $rowCount++;
                    if ($header === null) {
                        $header = $row; // Capture the header row
                        // Optional: Validate header columns if needed
                        continue; // Skip header row from processing
                    }

                    // Map CSV columns to database columns based on your CSV structure
                    // Assuming CSV columns are in order: 0=?, 1=mxl, 2=abc, 3=title, 4=composer, 5=n_tracks, 6=score, 7=tracks_y, 8=ori_key, 9=song_length.bars, 10=n_notes
                    $data = [
                        'mxl_path'         => $row[1] ?? null,
                        'abc_filename'     => $row[2] ?? null, // Make sure this column exists and is not null in CSV if DB requires it
                        'title'            => $row[3] ?? null,
                        'composer_name'    => $row[4] ?? null,
                        'n_tracks'         => isset($row[5]) && is_numeric($row[5]) ? (int)$row[5] : null,
                        'score'            => $row[6] ?? null,
                        'tracks_y'         => $row[7] ?? null,
                        'original_key'     => $row[8] ?? null,
                        'song_length_bars' => isset($row[9]) && is_numeric($row[9]) ? (int)$row[9] : null,
                        'n_notes'          => isset($row[10]) && is_numeric($row[10]) ? (int)$row[10] : null,
                    ];

                    // Basic validation: Ensure abc_filename is present as it's not nullable in DB
                    if (empty($data['abc_filename'])) {
                         $this->warn("Skipping row {$rowCount}: Missing abc_filename.");
                         continue;
                    }

                    AbcFile::create($data); // Create a new record in the database
                    $importedCount++;
                }
            } catch (Exception $e) {
                $this->error("An error occurred during import on row {$rowCount}: " . $e->getMessage());
                Log::error("CSV Import Error: " . $e->getMessage(), ['row' => $rowCount, 'data' => $row ?? null]); // Log detailed error
            } finally {
                fclose($handle);
            }

            $this->info("Import finished. Processed {$rowCount} rows (including header). Successfully imported {$importedCount} records.");
        } else {
            $this->error("Could not open the CSV file: {$csvPath}");
            return 1;
        }

        return 0; // Return success code
    }
}
