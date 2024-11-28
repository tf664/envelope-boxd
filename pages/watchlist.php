<?php
session_start();
require('../loginSystem/connection.php');

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    echo "You must be logged in to view your watchlist.";
    exit();
}

// Get the user's ID based on the session username
$user = $con->prepare("SELECT id FROM users WHERE username = :username");
$user->bindParam(":username", $_SESSION['username']);
$user->execute();
$user = $user->fetch(PDO::FETCH_ASSOC);

// Check if user exists
if (!$user) {
    echo "User not found.";
    exit();
}

$userId = $user['id']; // Store user ID

// Fetch all movie IDs from the watchlist
$query = "SELECT movie_id FROM watchlist WHERE user_id = :user_id";
$statement = $con->prepare($query);
$statement->bindParam(":user_id", $userId);
$statement->execute();
$movieIds = $statement->fetchAll(PDO::FETCH_ASSOC);

// If the user has no movies in the watchlist
if (empty($movieIds)) {
    echo "<p>Your watchlist is empty. Add some movies!</p>";
    exit();
}

// Extract movie IDs into an array
$movieIdArray = array_column($movieIds, 'movie_id');

// Convert array to comma-separated string for API call
$movieIdList = implode(",", $movieIdArray);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Watchlist</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>

<header>
    <?php include '../header.php'; ?>
</header>

<main>
    <h1>Your Watchlist</h1>

    <!-- Display movie list -->
    <div id="movie-container" class="movie-container"></div>

    <script>
        // Fetch movie details from IMDb API using the stored movie IDs
        async function fetchMovies() {
            const movieIds = <?php echo json_encode($movieIdList); ?>; // Pass PHP movie IDs to JS
            const movies = [];

            for (const movieId of movieIds.split(',')) {
                const url = `https://online-movie-database.p.rapidapi.com/v2/search?searchTerm=${encodeURIComponent(movieId)}`;
                const options = {
                    method: 'GET',
                    headers: {
                        'x-rapidapi-key': 'a04bf9fee4msh9eee9a62a7091abp13defdjsn5191c7091294',
                        'x-rapidapi-host': 'online-movie-database.p.rapidapi.com'
                    }
                };

                try {
                    const response = await fetch(url, options);
                    if (response.status === 429) {
                        console.error("API Rate limit exceeded");
                        return;
                    }

                    if (!response.ok) {
                        console.error(`Error fetching movies: ${response.statusText}`);
                        return;
                    }

                    const result = await response.json();
                    if (result?.data?.mainSearch?.edges) {
                        const movie = result.data.mainSearch.edges[0].node.entity;
                        if (movie) {
                            movies.push({
                                title: movie.titleText?.text || 'Unknown Title',
                                imageUrl: movie.primaryImage?.url || 'placeholder.png',
                                id: movie.id
                            });
                        }
                    }
                } catch (error) {
                    console.error("Error fetching movies:", error);
                }
            }

            return movies;
        }

        // Display movies in the container
        async function displayMovies() {
            const movieContainer = document.getElementById('movie-container');
            const movies = await fetchMovies();

            if (movies.length === 0) {
                movieContainer.innerHTML = "<p>No movies in your watchlist.</p>";
                return;
            }

            movieContainer.innerHTML = ''; // Clear previous content

            for (const movie of movies) {
                const movieItem = document.createElement('div');
                movieItem.classList.add('movie-item');
                movieItem.innerHTML = `
                    <a href="pages/specificMovie.php?title=${encodeURIComponent(movie.title)}" class="movie-link">
                        <img src="${movie.imageUrl}" alt="${movie.title}" class="movie-poster">
                        <p class="movie-title">${movie.title}</p>
                    </a>
                    <button class="remove-from-watchlist" data-movie-id="${movie.id}">Remove from Watchlist</button>
                `;

                movieContainer.appendChild(movieItem);
            }
        }

        // Initialize movie display
        document.addEventListener('DOMContentLoaded', displayMovies);
    </script>
</main>

<footer>
    <p style="text-align: center;" class="footer">EnvelopeBaskd</p>
</footer>

</body>
</html>
