<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ExactMatchSearchEngine;
use Illuminate\Support\Facades\DB;

class ExactMatchSearchEngineTest extends TestCase
{
    protected $searchEngine;
    protected $testJsonContent = '[{
        "info": {
            "filename": "Qma1c8cYKSTUaoTUjozYwYQkAxxntNQ3Zwb1sM1kPK8eC1.abc"
        },
        "variation": "V:1\n!f! A2 AB cBAG | A2 GA E4 | A2 AB cBcd | edef e4 | A2 AB cBAG | A2 GA E4 | c2 dc BcBG |"
    }]';

    protected function setUp(): void
    {
        parent::setUp();
        $this->searchEngine = new ExactMatchSearchEngine();

        // Create temporary test JSON file
        file_put_contents(database_path('abc_tunes.json'), $this->testJsonContent);

        // Mock the database response with actual data
        DB::shouldReceive('table')
            ->with('PDMX.abc_files')
            ->andReturnSelf();

        DB::shouldReceive('select')
            ->andReturnSelf();

        DB::shouldReceive('where')
            ->andReturnSelf();

        DB::shouldReceive('first')
            ->andReturn([
                'abc_filename' => 'Qma1c8cYKSTUaoTUjozYwYQkAxxntNQ3Zwb1sM1kPK8eC1.abc',
                'title' => 'Luigi Legnani - Caprice No. 20',
                'composer_name' => 'Luigi Legnani(1790 - 1877)',
                'n_tracks' => 1,
                'tracks_y' => 'V:1 treble nm=""Piano"" snm=""Pno."", V:2 bass',
                'original_key' => 'C',
                'song_length_bars' => 80
            ]);
    }

    public function test_can_find_exact_match()
    {
        // Test with an actual sequence from the file
        $query = "A2 AB cBAG";

        $results = $this->searchEngine->search($query);

        $this->assertIsArray($results);
        $this->assertArrayHasKey('results', $results);
        $this->assertCount(1, $results['results']);
        $this->assertEquals('Qma1c8cYKSTUaoTUjozYwYQkAxxntNQ3Zwb1sM1kPK8eC1.abc', $results['results'][0]['filename']);

        // Test metadata
        $this->assertArrayHasKey('metadata', $results['results'][0]);
        $metadata = $results['results'][0]['metadata'];
        $this->assertEquals('Luigi Legnani - Caprice No. 20', $metadata['title']);
        $this->assertEquals('Luigi Legnani(1790 - 1877)', $metadata['composer_name']);
        $this->assertEquals(1, $metadata['n_tracks']);
        $this->assertEquals('C', $metadata['original_key']);
        $this->assertEquals(80, $metadata['song_length_bars']);
    }

    public function test_returns_empty_results_for_no_match()
    {
        $query = "This sequence doesn't exist";

        $results = $this->searchEngine->search($query);

        $this->assertIsArray($results);
        $this->assertArrayHasKey('results', $results);
        $this->assertEmpty($results['results']);
    }

    public function test_file_not_found_handling()
    {
        // Delete the test file to simulate missing file
        unlink(database_path('abc_tunes.json'));

        $results = $this->searchEngine->search('test');

        $this->assertIsArray($results);
        $this->assertArrayHasKey('error', $results);
        $this->assertEmpty($results['results']);
    }

    protected function tearDown(): void
    {
        // Clean up the test JSON file
        if (file_exists(database_path('abc_tunes.json'))) {
            unlink(database_path('abc_tunes.json'));
        }
        parent::tearDown();
    }
}