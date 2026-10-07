<?php
session_start();
include '../connection.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email =$_POST['email'];
    $password = $_POST['password'];
    $device_info = $_POST['device_info'] ?? 'UNKNOWN'; // Device info from JS

    $stmt = $conn->prepare("SELECT * FROM doctor WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Check if account is blocked
        if ($user['availability'] === 'Blocked') {
            echo "<script>alert('Your account has been blocked. Please contact support.'); window.history.back();</script>";
            exit();
        }
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['doctor_id'] = $user['doctor_id'];
            $_SESSION['email'] = $user['email'];

            // 📌 Get Public IP Address
            $ip = @file_get_contents("https://api64.ipify.org?format=json");
            $ip = json_decode($ip, true)['ip'] ?? 'UNKNOWN';

            // 📌 Get Location (Using IP API)
            $location = "Unknown";
            if ($ip !== 'UNKNOWN') {
                $locData = @file_get_contents("http://ip-api.com/json/{$ip}");
                $loc = json_decode($locData, true);
                if ($loc && $loc['status'] === 'success') {
                    $location = $loc['city'] . ", " . $loc['country'];
                }
            }

            // ✅ Log Info Insert
            $logQuery = $conn->prepare("INSERT INTO log_info (doctor_id, login_time, ip_address, device_info, location, status, log_type) VALUES (?, NOW(), ?, ?, ?, 'Success', 'doctor')");
            $logQuery->bind_param("isss", $user['doctor_id'], $ip, $device_info, $location);
            $logQuery->execute();

            // ✅ Redirect to Dashboard
            echo "<script>window.location.href='doctor-dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No account found with this email.'); window.history.back();</script>";
    }
}
?>
