<?php
session_start();

if (empty($_SESSION["username"])) {
    header("Location: loginSystem/login.php");
    exit();
}
if ($_SERVER['REQUEST_URI'] == '/EnvelopeBaskd/envelope-baskd/') {
    header('Location: /EnvelopeBaskd/envelope-baskd/index.php');
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
<header>
    <!-- Top Banner -->
    <?php include 'header.php'; ?>
    </header>

    <main>
        <h1>Searched Movies:</h1>
        <div class="movie-container" id="movie-list"> 
    </main>

    <!-- Debugging -->

    <script src="api/getMovies.js"></script>
    <script src="api/searchbar.js"></script> <!-- Toggling the search bar -->
</body>

</html>