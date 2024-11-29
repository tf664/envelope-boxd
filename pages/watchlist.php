<?php
session_start();

if (empty($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: /EnvelopeBaskd/envelope-baskd/loginSystem/login.php");
    exit();
}

require('../loginSystem/connection.php');

// Get the user's ID based on the session username
$userStmt = $con->prepare("SELECT id FROM users WHERE username = :username");
$userStmt->bindParam(":username", $_SESSION['username']);
$userStmt->execute();
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit();
}

$userId = $user['id'];

// Fetch all of the movie's information from the user's watchlist
$query = "SELECT movie_id, movie_title, movie_poster FROM watchlist WHERE user_id = :user_id";
$statement = $con->prepare($query);
$statement->bindParam(":user_id", $userId);
$statement->execute();
$movies = $statement->fetchAll(PDO::FETCH_ASSOC);
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

        <p id="watchlist-message" style="color: #333; margin-top: 10px; opacity: 0.6; font-size: 0.8em"></p>

        <div id="movie-container" class="movie-container">
            <?php if (empty($movies)): ?>
                <p>Your watchlist is empty.</p>
            <?php else: ?>
                <?php foreach ($movies as $movie): ?>
                    <div class="movie-item">
                        <a href="/EnvelopeBaskd/envelope-baskd/pages/specificMovie.php?title=<?php echo urlencode($movie['movie_title']); ?>"
                            class="movie-link">
                            <img src="<?php echo htmlspecialchars($movie['movie_poster']); ?>"
                                alt="<?php echo htmlspecialchars($movie['movie_title']); ?>" class="movie-poster">
                            <p class="movie-title"><?php echo htmlspecialchars($movie['movie_title']); ?></p>
                        </a>
                        <button class="remove-from-watchlist"
                            data-movie-id="<?php echo htmlspecialchars($movie['movie_id']); ?>">Remove from Watchlist</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>


        <script>
            document.querySelectorAll('.remove-from-watchlist').forEach(button => {
                button.addEventListener('click', function () {
                    const movieId = this.getAttribute('data-movie-id');
                    const movieItem = this.closest('.movie-item'); // Get the parent .movie-item element for removal
                    const messageElement = document.getElementById('watchlist-message');

                    // Send AJAX request to remove the movie from the watchlist
                    fetch('/EnvelopeBaskd/envelope-baskd/watchlist/removeFromWatchlist.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `movie_id=${movieId}`
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
                            messageElement.textContent = "Failed to remove movie to watchlist.";
                            messageElement.style.color = '#333';
                            messageElement.textContent = '';
                        }, 1000);
                });
            });
        </script>

</body>
<footer>
    <p style="text-align: center;" class="footer">EnvelopeBaskd</p>
</footer>

</html>