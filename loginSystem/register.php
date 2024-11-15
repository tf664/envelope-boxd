<html>
<form action="register.php" method="post">
    <label for="username">Username:</label>
    <input id="username" name="username" required="" type="text" />
    <label for="email">Email:</label>
    <input id="email" name="email" required="" type="email" />
    <label for="password">Password:</label>
    <input id="password" name="password" required="" type="password" />
    <input name="register" type="submit" value="Register" />
</form>

</html>

<?php if (isset($_POST['register'])) {

    // Connect to the database 
    $mysqli = new mysqli("localhost", "username", "password", "login_system");

    // Check for errors 
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare and bind the SQL statement 
    $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    // Get the form data 
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password 
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Execute the SQL statement 
    if ($stmt->execute()) {
        echo "New account created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the connection 
    $stmt->close();
    $mysqli->close();
}