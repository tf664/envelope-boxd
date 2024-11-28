<?php
session_start();

$error_message = "";

require("connection.php");

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    $stmt = $con->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    $userAlreadyExists = $stmt->fetchColumn();

    if (strlen($username) < 1 || strlen($password) < 1) {
        $error_message = "Username and password must be at least 1 character long";
    } else if ($password != $confirm_password) {
        $error_message = "Passwords do not match";
    } else if ($userAlreadyExists) {
        $error_message = "User already exists";
    } else {
        $error_message = "";
        // Register
            // Hash the password with the bcrypt algorithm
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        registerUser($username, $hashed_password);
    }
}

function registerUser($username, $password)
{
    global $con;
    $stmt = $con->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password", $password);
    $stmt->execute();

    session_start();
    $_SESSION["username"] = $username;
    header("Location: ../index.php");
    exit();
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="loginSystemStyle.css">
    <title>Register</title>
</head>

<body>
    <!-- Registration form -->
    <div class="container">
        <form action="register.php" method="post">
            <h1>Register</h1>
            <div class="inputs_container">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <input type="submit" name="submit" value="Register">

            <!-- Error message -->
            <?php if (isset($_POST["submit"]) && !empty($error_message)) {
                echo "<div class='error-message'>$error_message</div>";
            } ?>
        </form>
    </div>
    <!-- Login redirect -->
    <div class="redirect">
        <p>Already have an account?</p>
        <a href="login.php" class="redirect-link">Press here to login</a>
    </div>
</body>

</html>