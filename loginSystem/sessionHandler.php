<?php
session_start();

// Prüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['user_id'])) {
    // Wenn der Benutzer nicht eingeloggt ist, leite zur Registrierungsseite weiter
    header('Location: register.php');
    exit();
}
?>