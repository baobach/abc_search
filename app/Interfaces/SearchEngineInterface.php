<?php

namespace App\Interfaces;

interface SearchEngineInterface
{
    /**
     * Search for a sequence in the database
     *
     * @param string $query The ABC notation sequence to search for
     * @param array $options Additional search options
     * @return array The search results
     */
    public function search(string $query, array $options = []): array;

    /**
     * Calculate similarity between two ABC sequences
     *
     * @param string $sequence1 First ABC sequence
     * @param string $sequence2 Second ABC sequence
     * @return float Similarity score between 0 and 1
     */
    public function calculateSimilarity(string $sequence1, string $sequence2): float;
}