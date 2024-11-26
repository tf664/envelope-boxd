<?php
if (isset($_GET['title'])) {
    // Movie title from the query string
    $movieTitle = urlencode($_GET['title']); // URL encode the title to make sure it's safe for the URL
    
    // RapidAPI IMDb search URL to get IMDb ID
    $searchUrl = "https://imdb8.p.rapidapi.com/v2/search?searchTerm=$movieTitle";
    
    // RapidAPI options
    $options = [
        "http" => [
            "header" => "x-rapidapi-key: 099c93e59bmsh5b63b7ecbb52195p1ea84djsn00ea468a0e19\r\n" .
                        "x-rapidapi-host: imdb8.p.rapidapi.com\r\n"
        ]
    ];
    $context = stream_context_create($options);
    $searchData = file_get_contents($searchUrl, false, $context);
    
    // Decode the JSON response into an associative array
    $searchResults = json_decode($searchData, true);

    // Ensure search response is valid
    if (!$searchResults || isset($searchResults['error']) || !isset($searchResults['data']['mainSearch']['edges'][0])) {
        echo "<p>Movie not found or invalid data.</p>";
        exit();
    }
    
    // Get IMDb ID from the search result (assumes first result is correct)
    $imdbId = $searchResults['data']['mainSearch']['edges'][0]['node']['entity']['id'];
    $title = $searchResults['data']['mainSearch']['edges'][0]['node']['entity']['titleText']['text'];
    $year = $searchResults['data']['mainSearch']['edges'][0]['node']['entity']['releaseYear']['year'];
    $posterUrl = $searchResults['data']['mainSearch']['edges'][0]['node']['entity']['primaryImage']['url'] ?? 'default-poster.jpg';
    $director = $searchResults['data']['mainSearch']['edges'][0]['node']['entity']['crew']['directors'][0]['name'] ?? 'Unknown Director';

    // Now use IMDb ID to fetch movie details (optional)
    // If you need further details, you can fetch them here with the IMDb ID

} else {
    header("Location: ../index.php"); // Redirect if no movie title is provided
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .movie-details {
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .movie-details img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .movie-details h1 {
            margin-top: 20px;
            font-size: 2em;
            color: #333;
        }
        .movie-details p {
            font-size: 1.2em;
            color: #555;
        }
        .movie-details .movie-title {
            font-weight: bold;
        }
        .movie-details .movie-attributes {
            margin-top: 15px;
        }
        .movie-details .movie-attributes strong {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <header>
        <h1>Movie Details</h1>
    </header>

    <main>
        <div class="movie-details">
            <!-- Movie Poster -->
            <img src="<?php echo $posterUrl; ?>" alt="<?php echo $title; ?>" class="movie-poster">
            
            <!-- Movie Title -->
            <h1><?php echo $title; ?></h1>

            <!-- Movie Attributes -->
            <div class="movie-attributes">
                <p><strong>Year:</strong> <?php echo $year; ?></p>
                <p><strong>Director:</strong> <?php echo $director; ?></p>
                <p><strong>Actors:</strong> <?php echo "Coming soon..."; ?></p> <!-- You can add actors if available -->
            </div>
        </div>
    </main>

    <footer>
        <!-- Footer Content (Optional) -->
        <p style="text-align: center;">&copy; 2024 Movie App</p>
    </footer>

</body>
</html>
