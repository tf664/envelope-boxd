<?php
$host = "localhost";
$name = "login";
$user = "root";
$password = "";

try {
    mysql = new PDO("mysql:host-$host;dbname-$name", $user, $password);
} catch (PDOException $e) {
    echo "SQL Error | ". $e->getMessage();
}

?>