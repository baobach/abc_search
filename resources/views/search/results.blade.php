<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ABC Search Results</title>

    <!-- Bootstrap and jQuery -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- ABCJS -->
    <script src="https://cdn.jsdelivr.net/npm/abcjs@6.2.2/dist/abcjs-basic-min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/abcjs@6.2.2/dist/abcjs-audio.css"/>

    <style>
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            padding: 20px 0;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        h1 {
            font-weight: 300;
            margin-top: 16px;
        }

        h1 b {
            color: #333;
        }

        .search-summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .result-item {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .metadata {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .metadata h3 {
            margin-top: 0;
            color: #333;
            font-size: 20px;
        }

        .metadata ul {
            list-style: none;
            padding-left: 0;
        }

        .metadata li {
            margin-bottom: 5px;
            color: #666;
        }

        .score-container {
            margin-top: 20px;
            padding: 10px;
            background: white;
        }

        .abcjs-inline-audio {
            margin: 10px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 15px;
            background: #f8f9fa;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
        }

        .back-link:hover {
            background: #e9ecef;
            text-decoration: none;
        }

        #warnings {
            font-size: 12px;
            color: red;
            margin-top: 8px;
        }

        footer {
            margin-top: 40px;
            padding: 20px 0;
            text-align: center;
            font-size: 14px;
            color: #666;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1><b>🎵 ABC Search</b> Results</h1>
        </div>

        <a href="{{ route('search.index') }}" class="back-link">← Back to search</a>

        <div class="search-summary">
            <p>Query: <strong>{{ $query }}</strong></p>
            <p>Found {{ count($results) }} results</p>
        </div>

        @if(count($results) > 0)
            <div class="results">
                @foreach($results as $result)
                    @php
                        $abcFilePath = base_path('database/abc/' . $result['filename']);
                        $fullAbcContent = file_exists($abcFilePath) ? file_get_contents($abcFilePath) : null;
                    @endphp

                    <div class="result-item">
                        @if(isset($result['metadata']))
                            <div class="metadata">
                                <h3>{{ $result['metadata']['title'] ?? 'Untitled' }}</h3>
                                <ul>
                                    <li><strong>Composer:</strong> {{ $result['metadata']['composer_name'] ?? 'Unknown' }}</li>
                                    <li><strong>Tracks:</strong> {{ $result['metadata']['n_tracks'] ?? 'N/A' }}</li>
                                    <li><strong>Key:</strong> {{ $result['metadata']['original_key'] ?? 'N/A' }}</li>
                                    <li><strong>Length:</strong> {{ $result['metadata']['song_length_bars'] ?? 'N/A' }} bars</li>
                                    <li><strong>File:</strong> {{ $result['filename'] }}</li>
                                </ul>
                            </div>
                        @endif

                        @if($fullAbcContent)
                            <div id="score-{{ $loop->index }}" class="score-container"></div>
                            <div id="audio-{{ $loop->index }}" class="audio-container"></div>
                        @else
                            <p class="error">ABC file not found: {{ $result['filename'] }}</p>
                        @endif
                    </div>

                    @if($fullAbcContent)
                        <script>
                            window.addEventListener('DOMContentLoaded', function() {
                                ABCJS.renderAbc(
                                    'score-{{ $loop->index }}',
                                    `{!! addslashes($fullAbcContent) !!}`,
                                    {
                                        responsive: 'resize',
                                        format: {
                                            titlefont: "Arial 16 bold",
                                            gchordfont: "Arial 12 bold",
                                            vocalfont: "Arial 14",
                                            composerfont: "Arial 12"
                                        },
                                        add_classes: true,
                                        paddingleft: 0,
                                        paddingright: 0,
                                        paddingbottom: 10,
                                        padddingtop: 10,
                                        staffwidth: 800
                                    }
                                );

                                if (ABCJS.synth.supportsAudio()) {
                                    new ABCJS.synth.CreateSynth();
                                    ABCJS.initAudioContext();
                                }
                            });
                        </script>
                    @endif
                @endforeach
            </div>
        @else
            <p class="text-center">No results found matching your query.</p>
        @endif

        <footer>
            <p>ABC Search Engine &copy; {{ date('Y') }}</p>
        </footer>
    </div>
</body>
</html>