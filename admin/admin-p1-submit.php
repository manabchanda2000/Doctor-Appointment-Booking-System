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
$name    = trim($_POST['name']);
$email   = trim($_POST['email']);
$phone   = trim($_POST['phone']);
$address = trim($_POST['address']);

// --- Handle profile image upload ---
$img_name = null;
if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $img_tmp  = $_FILES['logo']['tmp_name'];
    $img_ext  = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
    $img_name = 'admin_' . time() . '.' . $img_ext;
    move_uploaded_file($img_tmp, '../upload/admin/' . $img_name);
}

// --- Update Query ---
$sql = "UPDATE admin SET 
            name = ?, email = ?, phone = ?, address = ?, updated_at = NOW()";

if ($img_name) {
    $sql .= ", logo = ?";
}
$sql .= " WHERE admin_id = ?";

// ✅ Prepare Statement
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// ✅ Bind Parameters
if ($img_name) {
    $stmt->bind_param("sssssi", $name, $email, $phone, $address, $img_name, $admin_id);
} else {
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $admin_id);
}

// ✅ Execute and Check
if ($stmt->execute()) {
    echo "<script>alert('Admin details updated successfully'); window.location.href='admin-profile.php';</script>";
} else {
    echo "<script>alert('Something went wrong. Try again.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
