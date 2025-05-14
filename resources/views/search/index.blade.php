<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ABC Search Engine</title>

    <!-- Bootstrap and jQuery -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

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

        .search-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .search-description {
            text-align: center;
            margin-bottom: 30px;
            color: #666;
            font-size: 16px;
            line-height: 1.6;
        }

        .search-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }

        .form-control {
            height: 46px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .btn-search {
            width: 100%;
            height: 46px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
        }

        .btn-search:hover {
            background: #0056b3;
        }

        .example-section {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .example-section h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .example-code {
            background: white;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-family: monospace;
            margin-bottom: 15px;
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
            <h1><b>🎵 ABC Search</b> Engine</h1>
        </div>

        <div class="search-container">
            <div class="search-description">
                <p>Search for ABC notation sequences in our database. Enter a sequence of notes to find matching scores.</p>
            </div>

            <form action="{{ route('search') }}" method="GET" class="search-form">
                @csrf
                <div class="form-group">
                    <input type="text"
                           name="query"
                           class="form-control"
                           placeholder="Enter ABC notation sequence (e.g., A2 AB cBAG)"
                           required>
                </div>
                <button type="submit" class="btn btn-search">Search</button>
            </form>

            <div class="example-section">
                <h3>Example Sequences</h3>
                <div class="example-code">
                    A2 AB cBAG
                </div>
                <div class="example-code">
                    CDEF GABc
                </div>
                <p class="text-muted">Click an example to try it out</p>
            </div>
        </div>

        <footer>
            <p>ABC Search Engine &copy; {{ date('Y') }}</p>
        </footer>
    </div>

    <script>
        // Add click-to-try functionality for examples
        document.querySelectorAll('.example-code').forEach(function(element) {
            element.style.cursor = 'pointer';
            element.addEventListener('click', function() {
                document.querySelector('input[name="query"]').value = this.textContent.trim();
            });
        });
    </script>
</body>
</html>