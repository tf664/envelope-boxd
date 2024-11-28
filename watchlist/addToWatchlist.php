<?php
session_start(); 
require('../loginSystem/connection.php');

// Check if the session username is set
if (!isset($_SESSION['username'])) {
    echo "You need to be logged in to add movies to your watchlist.";
    exit();
}

// Get the user's ID based on the session username
$userStmt = $con->prepare("SELECT id FROM users WHERE username = :username");
$userStmt->bindParam(":username", $_SESSION['username']);
$userStmt->execute();
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

// Check if the user was found
if (!$user) {
    echo "User not found. Please log in.";
    exit();
}

$userId = $user['id']; 

// Checks if movie details are passed in the POST request
if (isset($_POST['movie_id'], $_POST['movie_title'], $_POST['movie_poster'])) {
    $movieId = $_POST['movie_id'];
    $movieTitle = $_POST['movie_title'];
    $moviePoster = $_POST['movie_poster'];

    try {
        // Checks if the movie is already in the watchlist
        $checkStmt = $con->prepare("SELECT * FROM watchlist WHERE user_id = :user_id AND movie_id = :movie_id");
        $checkStmt->bindParam(":user_id", $userId);
        $checkStmt->bindParam(":movie_id", $movieId);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            echo "This movie is already in your watchlist.";
            exit();
        }

        // Inserts the movie into the watchlist
        $insertStmt = $con->prepare(
            "INSERT INTO watchlist (user_id, movie_id, movie_title, movie_poster, added_at) 
            VALUES (:user_id, :movie_id, :movie_title, :movie_poster, NOW())"
        );
        $insertStmt->bindParam(":user_id", $userId);
        $insertStmt->bindParam(":movie_id", $movieId);
        $insertStmt->bindParam(":movie_title", $movieTitle);
        $insertStmt->bindParam(":movie_poster", $moviePoster);

        if ($insertStmt->execute()) {
            echo "Movie added to your watchlist!";
        } else {
            echo "Error: " . implode(", ", $insertStmt->errorInfo());
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
} else {
    echo "Invalid request. Missing movie data.";
}
?>
