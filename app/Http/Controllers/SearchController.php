<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExactMatchSearchEngine;
use App\Services\PatternMatchSearchEngine;

class SearchController extends Controller
{
    /**
     * Display the search form
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('search.index');
    }

    /**
     * Perform the search and show results
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $engineType = $request->input('engine', 'exact');

        // Create the appropriate search engine
        if ($engineType === 'exact') {
            $engine = new ExactMatchSearchEngine();
        } else {
            $engine = new PatternMatchSearchEngine();
        }

        // Perform the search
        $searchResults = $engine->search($query);

        // Return the view with search results
        return view('search.results', [
            'query' => $query,
            'engineType' => $engineType,
            'results' => $searchResults['results']
        ]);
    }
}
