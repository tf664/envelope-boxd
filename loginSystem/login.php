<?php
session_start();

if (isset($_POST['login'])) {

    // Include database connection
    include 'db_connect.php'; // Stelle sicher, dass diese Datei die Verbindung aufsetzt

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $username = htmlspecialchars(trim($_POST['username'])); // Input sanitization
    $password = $_POST['password'];
    $stmt->bind_param("s", $username);

    // Execute the SQL statement
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows > 0) {

        // Bind the result to variables
        $stmt->bind_result($id, $hashed_password);

        // Fetch the result
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Set the session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            // Redirect user to the intended page or homepage
            $redirect = isset($_SESSION['redirect_to']) ? $_SESSION['redirect_to'] : 'index.php';
            unset($_SESSION['redirect_to']); // Remove the session variable
            header("Location: $redirect");
            exit;
        } else {
            $error = "Invalid login credentials."; // General error message
        }
    } else {
        $error = "Invalid login credentials."; // General error message
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form action="login.php" method="post">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <label for="username">Username:</label>
        <input id="username" name="username" required type="text" />
        <label for="password">Password:</label>
        <input id="password" name="password" required type="password" />
        <input name="login" type="submit" value="Login" />
    </form>
</body>
</html>
