<?php
session_start();
include '../connection.php'; // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $device_info = $_POST['device_info'] ?? 'UNKNOWN';

    // 🔍 Get admin by email
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // ✅ Admin found
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
 
        // 🔐 Verify password
        if (password_verify($password, $admin['password'])) {
            // 🗝️ Set session
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_name'] = $admin['name'];

            // 🌐 Get IP
            $ip = @file_get_contents("https://api64.ipify.org?format=json");
            $ip = json_decode($ip, true)['ip'] ?? 'UNKNOWN';

            // 📍 Get location from IP
            $location = "Unknown";
            if ($ip !== 'UNKNOWN') {
                $locData = @file_get_contents("http://ip-api.com/json/{$ip}");
                $loc = json_decode($locData, true);
                if ($loc && $loc['status'] === 'success') {
                    $location = $loc['city'] . ", " . $loc['country'];
                }
            }

            // 📝 Log login
            $logStmt = $conn->prepare("INSERT INTO log_info (admin_id, login_time, ip_address, device_info, location, status, log_type) VALUES (?, NOW(), ?, ?, ?, 'Success', 'admin')");
            $logStmt->bind_param("isss", $admin['admin_id'], $ip, $device_info, $location);
            $logStmt->execute();

            // ➡️ Redirect to admin dashboard
            echo "<script>window.location.href='admin-dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect password.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('No admin account found with this email.'); window.history.back();</script>";
    }
}
?>
