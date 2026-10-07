<?php
session_start();
include '../connection.php';

// Verify user authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.html");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Sanitize and collect inputs
$linkedin  = trim($_POST['linkedin_link']);
$instagram = trim($_POST['instagram_link']);
$facebook  = trim($_POST['facebook_link']);
$youtube   = trim($_POST['youtube_link']);

// --- Update Query ---
$sql = "UPDATE admin SET 
            linkedin_link = ?, instagram_link = ?, facebook_link = ?, youtube_link = ?, updated_at = NOW()
        WHERE admin_id = ?";

// ✅ Prepare Statement
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// ✅ Bind Parameters
$stmt->bind_param("ssssi", $linkedin, $instagram, $facebook, $youtube, $admin_id);

// ✅ Execute and Check
if ($stmt->execute()) {
    echo "<script>alert('Social media links updated successfully'); window.location.href='admin-profile.php';</script>";
} else {
    echo "<script>alert('Something went wrong. Try again.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
