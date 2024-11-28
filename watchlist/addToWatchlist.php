<?php
session_start();
require('../loginSystem/connection.php');

// Query to get user information
$user = $con->prepare("SELECT id FROM users WHERE username = :username");
$user->bindParam(":username", $_SESSION['username']);
$user->execute();
$user = $user->fetch(PDO::FETCH_ASSOC);

// Checks if user exists
if (!$user) {
    echo "User not found.";
    exit();
}

// Extracts user_id from the fetched result
$userId = $user['id']; 

// Checks if movie_id is passed in the request
if (isset($_POST['movie_id'])) {
    $movieId = $_POST['movie_id'];

    try {
        // Check if movie is already in the watchlist
        $checkStmt = $con->prepare("SELECT * FROM watchlist WHERE user_id = :user_id AND movie_id = :movie_id");
        $checkStmt->bindParam(":user_id", $userId);
        $checkStmt->bindParam(":movie_id", $movieId);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            echo "This movie is already in your watchlist.";
            exit();
        }

        // Inserts data into watchlist
        $insertStmt = $con->prepare("INSERT INTO watchlist (user_id, movie_id, added_at) VALUES (:user_id, :movie_id, NOW())");
        $insertStmt->bindParam(":user_id", $userId);
        $insertStmt->bindParam(":movie_id", $movieId);

        if ($insertStmt->execute()) {
            echo "Movie added to your watchlist!";
        } else {
            echo "Error: " . implode(", ", $insertStmt->errorInfo()); 
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request."; 
}
?>