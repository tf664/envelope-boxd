<?php
session_start();
session_destroy(); // Beendet die Sitzung
header('Location: register.php'); // Leitet zur Registrierungsseite weiter
exit();
?>