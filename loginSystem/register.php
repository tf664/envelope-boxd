<?php
require("connection.php");

if (isset($_POST["submit"])) {
    var_dump($_POST);

    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username =:username");
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    $userAlreadyExists = $stmt->fetchColumn();

    if (!$userAlreadyExists) {
        // Register
        registerUser($username, $password);
    } else {
        echo "User already exists";
    }
}

function registerUser($username, $password)
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":password", $password);
    $stmt->execute();
    // Redirect to main page
    $_SESSION['user_id'] = $conn->lastInsertId(); // Setzt die Session mit der User-ID
    header('Location: index.php'); // Leitet den Benutzer weiter
    exit();
}

?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="loginSystemStyle.css">
    <title>Register</title>
</head>

<body>
    <form action="register.php" method="post">
        <div class="Register">
            <h1>Register</h1>
            <div class="inputs_container">
                <input type="text" name="username" placeholder="Username">
                <input type="password" name="password" placeholder="Password">
                <input type="password" name="confirm_password" placeholder="Confirm Password">
            </div>
            <input type="submit" name="submit" value="Register">
        </div>

</html>