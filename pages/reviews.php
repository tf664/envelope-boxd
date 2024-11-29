<?php
session_start();
require('../loginSystem/connection.php');

// Check if user is logged in
if (empty($_SESSION['username'])) {
    header("Location: /EnvelopeBaskd/envelope-baskd/loginSystem/login.php");
    exit();
}

// Get the user's ID based on the session username
$username = $_SESSION['username'];
$userStmt = $con->prepare("SELECT id FROM users WHERE username = :username");
$userStmt->bindParam(":username", $username);
$userStmt->execute();
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit();
}

$userId = $user['id'];

// Handle form submission to update review (now for individual reviews)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['movie_id']) && isset($_POST['user_id']) && isset($_POST['review_text']) && isset($_POST['rating'])) {
        // Get values from the form
        $movieId = (string) $_POST['movie_id']; // Make sure movie_id is a string to store IMDb ID (e.g., tt0097165)
        $reviewText = trim($_POST['review_text']);  // Trim and sanitize the review text
        $rating = (int) $_POST['rating'];

        // Validate rating to ensure it's within the correct range
        if ($rating < 1 || $rating > 10) {
            $rating = 1; // Default to 1 if rating is invalid
        }

        // Check if the review text is empty, if so, make it NULL
        if (empty($reviewText)) {
            $reviewText = NULL;
        }

        // Prepare and execute the update query for the specific movie
        $updateQuery = "UPDATE reviews SET review_text = :review_text, rating = :rating, review_date = NOW() 
                        WHERE user_id = :user_id AND movie_id = :movie_id";
        $updateStmt = $con->prepare($updateQuery);
        $updateStmt->bindParam(":review_text", $reviewText, PDO::PARAM_STR);
        $updateStmt->bindParam(":rating", $rating, PDO::PARAM_INT);
        $updateStmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
        $updateStmt->bindParam(":movie_id", $movieId, PDO::PARAM_STR); // Use string for IMDb ID

        // Execute the query for the current movie
        if ($updateStmt->execute()) {
            $_SESSION['message'] = "Review updated successfully!";
        } else {
            $_SESSION['message'] = "Error updating review for movie ID: $movieId.";
        }

        // Redirect back to the reviews page
        header("Location: reviews.php");
        exit();
    }
}

// Fetch the user's reviewed movies from the reviews table
$query = "SELECT * FROM reviews WHERE user_id = :user_id";
$stmt = $con->prepare($query);
$stmt->bindParam(":user_id", $userId);
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Review List</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <header>
        <?php include '../header.php'; ?>
    </header>

    <main>
        <h1>Your Review List</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Check if the user has reviews -->
        <?php if ($reviews): ?>
            <div class="reviews-container">
                <?php foreach ($reviews as $review): ?>
                    <!-- Start a new form for each review -->
                    <form method="POST" action="reviews.php">
                        <div class="review-item">
                            <div class="review-header">
                                <h3><?php echo htmlspecialchars($review['movie_title']); ?></h3>
                                <img src="<?php echo htmlspecialchars($review['movie_poster']); ?>" alt="Poster" class="movie-poster" />
                            </div>

                            <!-- Editable Review Text -->
                            <label for="review_text_<?php echo $review['movie_id']; ?>">Review:</label>
                            <textarea name="review_text" id="review_text_<?php echo $review['movie_id']; ?>" required>
                                <?php echo htmlspecialchars($review['review_text']); ?>
                            </textarea>

                            <!-- Editable Rating -->
                            <label for="rating_<?php echo $review['movie_id']; ?>">Rating:</label>
                            <select name="rating" id="rating_<?php echo $review['movie_id']; ?>" required>
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo $i == $review['rating'] ? 'selected' : ''; ?>>
                                        <?php echo $i; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>

                            <!-- Hidden fields for movie_id and user_id -->
                            <input type="hidden" name="movie_id" value="<?php echo $review['movie_id']; ?>">
                            <input type="hidden" name="user_id" value="<?php echo $userId; ?>">

                            <!-- Submit Button for this specific review -->
                            <button type="submit" class="submit-btn">Update Review</button>
                        </div>
                    </form>
                    <!-- End of one review form -->
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>You haven't reviewed any movies yet.</p>
        <?php endif; ?>

    </main>
</body>
</html>
