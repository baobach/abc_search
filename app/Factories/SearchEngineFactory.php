<?php

namespace App\Factories;

use App\Interfaces\SearchEngineInterface;
use App\Services\ExactMatchSearchEngine;
use App\Services\PatternMatchSearchEngine;
use InvalidArgumentException;

class SearchEngineFactory
{
    /**
     * Create a search engine instance
     *
     * @param string $type Type of search engine to create
     * @return SearchEngineInterface
     * @throws InvalidArgumentException
     */
    public function create(string $type): SearchEngineInterface
    {
        return match ($type) {
            'exact' => new ExactMatchSearchEngine(),
            'pattern' => new PatternMatchSearchEngine(),
            default => throw new InvalidArgumentException("Unsupported search engine type: {$type}"),
        };
    }
}