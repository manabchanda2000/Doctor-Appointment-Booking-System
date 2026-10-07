<?php
session_start();
include '../connection.php';

if (isset($_SESSION['doctor_id'])) {
    $patient_id = $_SESSION['doctor_id'];

    // ✅ Log logout time
    $update = $conn->prepare("UPDATE log_info SET logout_time = NOW() WHERE doctor_id = ? ORDER BY login_time DESC LIMIT 1");
    $update->bind_param("i", $patient_id);
    $update->execute();
}

// ✅ Destroy session securely
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session

// Prevent back button from accessing cached page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Location: doctor-login.html");
exit();
?>
