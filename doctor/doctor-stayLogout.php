<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor-login.html");
    exit();
}

// Prevent back after logout
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: -1");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
?>
