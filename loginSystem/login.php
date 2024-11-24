<?php
require("connection.php");

if(isset($_POST["submit"]) {
    var_dump($_POST);
}

<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="loginSystemStyle.css">
    <title>Login</title>
</head>
<body>
    <div class="login">
        <h1>Login</h1>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" name="submit" value="Login">
        </form>
    </div>

</html>