<?php
if (isset($_GET['title'])) {

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
    <link rel="stylesheet" href="../styles.css">
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

    <button id="add-to-watchlist" style="margin-top: -30px;"
        data-movie-id="<?php echo htmlspecialchars($movieData['id']); ?>">Add to Watchlist</button>
    <p id="watchlist-message" style="color: #333; margin-top: 10px; opacity: 0.6; font-size: 0.8em"></p>


    <footer>
        <p style="text-align: center;" class="footer">EnvelopeBaskd</p>
    </footer>
</body>
</html>

<!-- Logic for adding movie to watchlist -->
<script>
    document.getElementById('add-to-watchlist').addEventListener('click', function () {
    const movieId = this.getAttribute('data-movie-id');
    const messageElement = document.getElementById('watchlist-message');

    // Send AJAX request using fetch
    fetch('../watchlist/addToWatchlist.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `movie_id=${movieId}`
    })
    .then(response => response.text())  // Get the response text from the server
    .then(data => {
        // Update the message with the response
        messageElement.textContent = data;

        // Set the message color to #333 (dark gray)
        messageElement.style.color = '#333'; // Force color to #333

        // Hide the message after 1 second
        setTimeout(() => {
            messageElement.textContent = '';  // Clear the message
        }, 1000);  // 1000 ms = 1 second
    })
    .catch(error => {
        console.error('Error:', error);
        messageElement.textContent = "Failed to add movie to watchlist.";  // Show error message if fetch fails

        // Set the message color to #333 (dark gray) even on error
        messageElement.style.color = '#333'; // Force color to #333

        // Hide the message after 1 second
        setTimeout(() => {
            messageElement.textContent = '';  // Clear the message
        }, 1000);  // 1000 ms = 1 second
    });
});

</script>