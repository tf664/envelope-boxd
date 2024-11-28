<?php
session_start();
require('../loginSystem/connection.php');

// Get the user's ID based on the session username
$userStmt = $con->prepare("SELECT id FROM users WHERE username = :username");
$userStmt->bindParam(":username", $_SESSION['username']);
$userStmt->execute();
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

// Get the movie ID from the POST request
if (!isset($_POST['movie_id'])) {
    echo "No movie ID provided.";
    exit();
}

$movieId = $_POST['movie_id'];

// Gets the user's ID from the session
$userStmt = $con->prepare("SELECT id FROM users WHERE username = :username");
$userStmt->bindParam(":username", $_SESSION['username']);
$userStmt->execute();
$user = $userStmt->fetch(PDO::FETCH_ASSOC);


$userId = $user['id'];


$deleteStmt = $con->prepare("DELETE FROM watchlist WHERE user_id = :user_id AND movie_id = :movie_id");
$deleteStmt->bindParam(":user_id", $userId);
$deleteStmt->bindParam(":movie_id", $movieId);
$deleteStmt->execute();

if ($deleteStmt->rowCount() > 0) {
    echo "Movie removed from watchlist.";
} else {
    echo "Failed to remove movie or movie not found in your watchlist.";
}
?>