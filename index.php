<?php
session_start();

if (empty($_SESSION["username"])) {
    header("Location: loginSystem/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie List</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Top Banner -->
    <?php include 'header.php'; ?>

    <main>
        <h1>Searched Movies:</h1>
        <button id="scroll-left" class="scroll-button">⬅️</button>
        <div class="movie-container" id="movie-list"> <!-- The container will hold the movie items directly --> </div>
        <button id="scroll-right" class="scroll-button">➡️</button>
    </main>

    <!-- Debugging -->

    <script src="api/getMovies.js"></script>
    <script src="api/searchbar.js"></script> <!-- Toggling the search bar -->
    <script src="api/scroll.js"></script> <!-- Scroll buttons -->
</body>

</html>