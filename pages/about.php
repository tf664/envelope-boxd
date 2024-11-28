<?php
session_start();

if (empty($_SESSION['username'])) {
    header("Location: loginSystem/login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <header>
    <?php include '../header.php'; ?>
    </header>

    <main>
        <div class="movie-details">
            <h2>What is this project about?</h2>
            <p>I've created this website to be able to store movies via an API 
                in a watchlist and to review them. This is done with the 
                "Online Movie Database" from "Api Dojo". It allows me to have a quota of
            500 quotas per month for free.</p>
            <br>
            <p>Problems are:</p>
            <p> when having multiple reviews the review-text and rating 
                gets applied to all movies in the reviews database
            </p>
        </div>
        </main>

        <footer class="footer">
            <p>&copy; 2024 My Retro Movies. All Rights Reserved.</p>
        </footer>
</body>

</html>