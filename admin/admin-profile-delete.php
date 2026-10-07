<?php
session_start();
include '../connection.php';

// Verify user authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.html");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// --- Delete Query ---
$sql = "DELETE FROM admin WHERE admin_id = ?";

// ✅ Prepare Statement
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// ✅ Bind Parameters
$stmt->bind_param("i", $admin_id);

// ✅ Execute and Check
if ($stmt->execute()) {
    // Destroy session and redirect after successful deletion
    session_destroy();
    echo "<script>alert('Account deleted successfully'); window.location.href='admin-login.html';</script>";
} else {
    echo "<script>alert('Something went wrong. Try again.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
