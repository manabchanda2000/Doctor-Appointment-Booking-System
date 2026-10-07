<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if OTP is set
    if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_expiry'])) {
        echo json_encode(["status" => "error", "message" => "OTP expired or not generated."]);
        exit;
    }

    // Get OTP from user input
    $entered_otp = trim($_POST['otp']);
    $stored_otp = $_SESSION['otp'];
    $otp_expiry = $_SESSION['otp_expiry'];

    // Check if OTP is still valid (2 minutes)
    if (time() > $otp_expiry) {
        unset($_SESSION['otp']);
        unset($_SESSION['otp_expiry']);
        echo json_encode(["status" => "error", "message" => "OTP expired. Please request a new one."]);
        exit;
    }

    // Verify OTP
    if ($entered_otp != $stored_otp) {
        echo json_encode(["status" => "error", "message" => "Invalid OTP. Please try again."]);
        exit;
    }

    // Update Password in Database
    if (!isset($_SESSION['new_password'])) {
        echo json_encode(["status" => "error", "message" => "New password is missing."]);
        exit;
    }

    $doctor_id = $_SESSION['doctor_id'];
    $new_password = password_hash($_SESSION['new_password'], PASSWORD_BCRYPT); // Hash new password

    // Update password in database
    $stmt = $conn->prepare("UPDATE doctor SET password = ? WHERE doctor_id = ?");
    $stmt->bind_param("si", $new_password, $doctor_id);

    if ($stmt->execute()) {
        // Clear OTP session data
        unset($_SESSION['otp']);
        unset($_SESSION['otp_expiry']);
        unset($_SESSION['new_password']);

        echo json_encode(["status" => "success", "message" => "Password changed successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update password. Try again."]);
    }

    $stmt->close();
    $conn->close();
}
?>
