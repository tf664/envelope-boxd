<?php
if (isset($_GET['title'])) {
    // Movie title from the query string
    $movieTitle = urlencode($_GET['title']); // URL encode the title to make sure it's safe for the URL

    // RapidAPI IMDb search URL to get IMDb ID
    $searchUrl = "https://online-movie-database.p.rapidapi.com/v2/search?searchTerm=$movieTitle";

    $options = [
        "http" => [
            "header" => "x-rapidapi-key: a04bf9fee4msh9eee9a62a7091abp13defdjsn5191c7091294\r\n" .
                "online-movie-database.p.rapidapi.com\r\n"
        ]
    ];
    $context = stream_context_create($options);
    $searchData = file_get_contents($searchUrl, false, $context);

    // Check if the response was fetched correctly
    if ($searchData === false) {
        echo "<p>Failed to fetch data from API.</p>";
        exit();
    }

    // Decode the JSON response into an associative array
    $searchResults = json_decode($searchData, true);

    // Ensure search response is valid
    if (!$searchResults || isset($searchResults['error']) || !isset($searchResults['data']['mainSearch']['edges'][0])) {
        echo "<p>Movie not found or invalid data.</p>";
        exit();
    }

    // Get IMDb ID from the search result (assumes first result is correct)
    $movieData = $searchResults['data']['mainSearch']['edges'][0]['node']['entity'];

    // Extract movie details
    $movieData = $searchResults['data']['mainSearch']['edges'][0]['node']['entity'];
    $title = $movieData['titleText']['text'] ?? 'Unknown Title';
    $year = $movieData['releaseYear']['year'] ?? 'Unknown Year';
    $posterUrl = $movieData['primaryImage']['url'] ?? 'default-poster.jpg';


    if (isset($movieData['principalCredits'])) {
        $principalCredits = [];
        // Loop through the principal credits and collect each name and image URL
        foreach ($movieData['principalCredits'] as $creditsData) {
            foreach ($creditsData['credits'] as $credit) {
                // Store each name and image URL in the array
                if (isset($credit['name']['nameText']['text'])) {
                    $principalCredits[] = [
                        'name' => $credit['name']['nameText']['text'],
                    ];
                }
            }
        }
    }
    if (!empty($principalCredits)) {
        $creditNames = [];
        foreach ($principalCredits as $credit) {
            $creditNames[] = $credit['name'];
        }

        $stars = htmlspecialchars(implode(", ", $creditNames));
    } else {
        $stars = "No principal credits available.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="../headerStyle.css">
</head>
<body>

<header>
    <?php include '../header.php'; ?>
    </header>

    <main>
        <div class="movie-details">
            <img src="<?php echo htmlspecialchars($posterUrl); ?>" alt="<?php echo htmlspecialchars($title); ?>">
            <h1><?php echo htmlspecialchars($title); ?></h1>
            <div class="movie-attributes">
                <p><strong>Year:</strong> <?php echo htmlspecialchars($year); ?></p>
                <p><strong>Stars:</strong> <?php echo $stars; ?></p>
            </div>
        </div>
    </main>

    <footer>
        <p style="text-align: center;">EnvelopeBaskd</p>
    </footer>

</body>

</html>