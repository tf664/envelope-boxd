<?php
session_start();

// Check if the user is logged in
if (empty($_SESSION['username'])) {
    echo "You need to be logged in to add movies to your review list.";
    exit();
}

// Include the database connection file
require('../loginSystem/connection.php');

// Get the user's ID from the session
$userStmt = $con->prepare("SELECT id FROM users WHERE username = :username");
$userStmt->bindParam(":username", $_SESSION['username']);
$userStmt->execute();
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit();
}

$userId = $user['id'];

// Check if movie data was provided in the request
if (isset($_POST['movie_id'], $_POST['movie_title'], $_POST['movie_poster'])) {
    // Ensure movie_id is an integer
    $movieId = (int) $_POST['movie_id'];
    $movieTitle = $_POST['movie_title'];
    $moviePoster = $_POST['movie_poster'];

    // Insert the movie into the reviews table with empty review text and NULL rating
    $query = "INSERT INTO reviews (user_id, movie_id, movie_title, movie_poster, review_text, rating, review_date) 
                  VALUES (:user_id, :movie_id, :movie_title, :movie_poster, '', NULL, NOW())"; // NULL for rating
    $stmt = $con->prepare($query);
    $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
    $stmt->bindParam(":movie_id", $movieId, PDO::PARAM_INT);
    $stmt->bindParam(":movie_title", $movieTitle);
    $stmt->bindParam(":movie_poster", $moviePoster);

    if ($stmt->execute()) {
        echo "Movie added to your review list!";
        exit();
    } else {
        echo "Error adding movie to review list.";
        error_log("Error: " . implode(", ", $stmt->errorInfo())); // Log any SQL errors
        exit();
    }
} else {
    echo "Required movie details not provided.";
    exit();
}
?>