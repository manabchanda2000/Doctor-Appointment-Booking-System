<?php
session_start();
include '../connection.php';

$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    header("Location: admin-login.php");
    exit();
}

// Get form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$address = $_POST['address'] ?? '';
$logo_name = '';

// Check if a logo file is uploaded
if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
    $upload_dir = '../upload/admin/';
    $file_tmp = $_FILES['logo']['tmp_name'];
    $file_name = time() . '_' . basename($_FILES['logo']['name']);
    $target_file = $upload_dir . $file_name;

    // Only allow image files
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($file_type, $allowed_types)) {
        if (move_uploaded_file($file_tmp, $target_file)) {
            $logo_name = $file_name;
        }
    }
}

// If logo is uploaded, update everything including logo
if (!empty($logo_name)) {
    $sql = "UPDATE admin SET company_name = ?, email = ?, phone = ?, address = ?, logo = ? WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name, $email, $phone, $address, $logo_name, $admin_id);
} else {
    // Update without logo
    $sql = "UPDATE admin SET company_name = ?, email = ?, phone = ?, address = ? WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $admin_id);
}

if ($stmt->execute()) {
    // Success - redirect back to profile
    header("Location: admin-profile.php?success=1");
    exit();
} else {
    echo "Error updating profile: " . $stmt->error;
}
?>
