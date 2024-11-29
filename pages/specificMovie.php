<?php
session_start();

if (empty($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: /EnvelopeBaskd/envelope-baskd/loginSystem/login.php");
    exit();
}

if (isset($_GET['title'])) {
    $movieTitle = urlencode($_GET['title']); // URL encode the title to make sure it's safe for the URL

    // API search to get IMDb ID
    $searchUrl = "https://online-movie-database.p.rapidapi.com/v2/search?searchTerm=$movieTitle";
    $options = [
        "http" => [
            "header" => "x-rapidapi-key: d3c650deedmsh7c3e51e84954f61p1aa812jsn45d37325bbd5\r\n" .
                "online-movie-database.p.rapidapi.com\r\n"
        ]
    ];
    $context = stream_context_create($options);
    $searchData = file_get_contents($searchUrl, false, $context);

    if ($searchData === false) {
        echo "<p>Failed to fetch data from API.</p>";
        exit();
    }

    // Decodes the JSON response into an associative array
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
        // Loops through the principal credits and collect each name
        foreach ($movieData['principalCredits'] as $creditsData) {
            foreach ($creditsData['credits'] as $credit) {
                // Stores each name  in the array
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
    <link rel="stylesheet" href="/EnvelopeBaskd/envelope-baskd/styles.css">
</head>

<>
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
        data-movie-id="<?php echo htmlspecialchars($movieData['id']); ?>"
        data-movie-title="<?php echo htmlspecialchars($title); ?>"
        data-movie-poster="<?php echo htmlspecialchars($posterUrl); ?>">Add to Watchlist</button>
    <br>
    <button id="add-to-review-list" style="margin-top: -10px;"
        data-movie-id="<?php echo htmlspecialchars($movieData['id']); ?>"
        data-movie-title="<?php echo htmlspecialchars($title); ?>"
        data-movie-poster="<?php echo htmlspecialchars($posterUrl); ?>">Add to Review List</button>
    <br>
    <p id="watchlist-message" style="color: #333; margin-top: 10px; opacity: 0.6; font-size: 0.8em"></p>
    <p id="review-list-message" style="color: #333; margin-top: 10px; opacity: 0.6; font-size: 0.8em"></p>



    <footer>
        <p style="text-align: center;" class="footer">EnvelopeBaskd</p>
    </footer>
    </body>

</html>


<!-- Logic with AJAX -->
<script>
    document.getElementById('add-to-watchlist').addEventListener('click', function () {
        const movieId = this.getAttribute('data-movie-id');
        const movieTitle = this.getAttribute('data-movie-title');
        const moviePoster = this.getAttribute('data-movie-poster');
        const messageElement = document.getElementById('watchlist-message');

        // Sends AJAX request using fetch
        fetch('/EnvelopeBaskd/envelope-baskd/watchlist/addToWatchlist.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `movie_id=${movieId}&movie_title=${encodeURIComponent(movieTitle)}&movie_poster=${encodeURIComponent(moviePoster)}`
        })
            // Gets the response text from the server
            .then(response => response.text())
            .then(data => {
                messageElement.textContent = data;
                messageElement.style.color = '#333';

                setTimeout(() => {
                    messageElement.textContent = '';
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                messageElement.textContent = "Failed to add movie to watchlist.";
                messageElement.style.color = '#333';
                messageElement.textContent = '';
            }, 1000);
    });

    // Review add logic
    document.getElementById('add-to-review-list').addEventListener('click', function () {
        const movieId = this.getAttribute('data-movie-id');
        const movieTitle = this.getAttribute('data-movie-title');
        const moviePoster = this.getAttribute('data-movie-poster');
        const messageElement = document.getElementById('review-list-message');

        console.log("Adding Movie:", movieId, movieTitle); // Debugging line to confirm movie_id is being passed

        // Send AJAX request using fetch to add the movie to the review list
        fetch('/EnvelopeBaskd/envelope-baskd/review/addToReview.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `movie_id=${movieId}&movie_title=${encodeURIComponent(movieTitle)}&movie_poster=${encodeURIComponent(moviePoster)}`
        })
            .then(response => response.text())
            .then(data => {
                messageElement.textContent = data;
                messageElement.style.color = '#333';

                setTimeout(() => {
                    messageElement.textContent = '';
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                messageElement.textContent = "Failed to add movie to review list.";
                messageElement.style.color = 'red';
                setTimeout(() => {
                    messageElement.textContent = '';
                }, 1000);
            });
    });
</script>