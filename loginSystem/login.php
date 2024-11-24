<?php
session_start();

$error_message = "";
require("connection.php");

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare and execute query
    $stmt = $con->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    $userExists = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userExists) {
        $passwordHashed = $userExists["password"];
        $checkPassword = password_verify($password, $passwordHashed);
        if ($checkPassword === false) {
            $error_message = "Invalid username or password";
        } else if ($checkPassword === true) {
            // Logins the user
            $_SESSION["username"] = $userExists["username"];
            header("Location: ../index.php");  // Redirect to the home page
            exit();
        }
    } else {
        $error_message = "Invalid username or password";
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="loginSystemStyle.css">
    <title>Login</title>
</head>

<body>
    <!-- Login form -->
    <div class="container">
        <form action="login.php" method="post">
            <h1>Login</h1>
            <div class="inputs_container">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <input type="submit" name="submit" value="Login">

            <!-- Error message -->
            <?php if (!empty($error_message)) {
                echo "<div class='error-message'>$error_message</div>";
            } ?>
        </form>
    </div>
    <!-- Register redirect -->
    <div class="redirect">
        <p>Have no account?</p>
        <a href="register.php" class="redirect-link">Press here to register</a>
    </div>
</body>

</html>