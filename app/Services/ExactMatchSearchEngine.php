<?php

namespace App\Services;

ini_set('memory_limit', '512M'); // Set memory limit to 512MB

use App\Interfaces\SearchEngineInterface;
use Illuminate\Support\Facades\DB;

class ExactMatchSearchEngine implements SearchEngineInterface
{
    public function search(string $query, array $options = []): array
    {
        try {
            $jsonPath = database_path('abc_tunes.json');

            if (!file_exists($jsonPath)) {
                throw new \Exception('JSON file not found at: ' . $jsonPath);
            }

            // Read the entire JSON file
            $jsonContent = file_get_contents($jsonPath);
            $tunes = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON format: ' . json_last_error_msg());
            }

            $matches = [];
            foreach ($tunes as $tune) {
                // Skip if required fields are missing
                if (!isset($tune['variation']) || !isset($tune['info']['filename'])) {
                    continue;
                }

                // Simple string search in the variation field
                if (strpos($tune['variation'], $query) !== false) {
                    // Get metadata from database using the filename
                    $metadata = DB::table('PDMX.abc_files')
                        ->select([
                            'abc_filename',
                            'title',
                            'composer_name',
                            'n_tracks',
                            'tracks_y',
                            'original_key',
                            'song_length_bars'
                        ])
                        ->where('abc_filename', $tune['info']['filename'])
                        ->first();

                    $matches[] = [
                        'filename' => $tune['info']['filename'],
                        'content' => $tune['variation'],
                        'metadata' => $metadata ? (array)$metadata : null
                    ];
                }
            }

            // Debug information
            \Log::debug('Search Query: ' . $query);
            \Log::debug('Number of matches found: ' . count($matches));

            return [
                'engine' => 'exact-match',
                'query' => $query,
                'results' => $matches
            ];

        } catch (\Exception $e) {
            \Log::error('Error in ExactMatchSearchEngine: ' . $e->getMessage());
            return [
                'engine' => 'exact-match',
                'query' => $query,
                'results' => [],
                'error' => $e->getMessage()
            ];
        }
    }

    public function calculateSimilarity(string $sequence1, string $sequence2): float
    {
        // Not used for exact matching
        return 0.0;
    }
}