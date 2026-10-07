<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.html");
    exit();
}

$patient_id = $_SESSION['patient_id'];

// Sanitize and collect inputs
$name     = trim($_POST['name']);
$email    = trim($_POST['email']);
$phone    = trim($_POST['phone']);
$dob      = $_POST['dob'];
$gender   = $_POST['gender'] ?? null;
$blood    = $_POST['blood_group'] ?? null;
$contact  = trim($_POST['emergency_contact']);
$area     = trim($_POST['area']);
$city     = trim($_POST['city']);
$state    = trim($_POST['state']);
$pincode  = trim($_POST['pincode']);

// --- Handle profile image upload ---
$img_name = null;
if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
    $img_tmp  = $_FILES['profile_img']['tmp_name'];
    $img_ext  = pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION);
    $img_name = 'patient_' . time() . '.' . $img_ext;
    move_uploaded_file($img_tmp, '../upload/patient/' . $img_name);
}

// --- Update Query ---
$sql = "UPDATE patient SET 
            name = ?, email = ?, phone = ?, dob = ?, gender = ?, blood_group = ?, 
            emergency_contact = ?, area = ?, city = ?, state = ?, pincode = ?, updated_at = NOW()";

if ($img_name) {
    $sql .= ", profile_img = ?";
}
$sql .= " WHERE patient_id = ?";

// ✅ Prepare Statement
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// ✅ Bind Parameters
if ($img_name) {
    $stmt->bind_param("ssssssssssssi", $name, $email, $phone, $dob, $gender, $blood,
        $contact, $area, $city, $state, $pincode, $img_name, $patient_id);
} else {
    $stmt->bind_param("sssssssssssi", $name, $email, $phone, $dob, $gender, $blood,
        $contact, $area, $city, $state, $pincode, $patient_id);
}

// ✅ Execute and Check
if ($stmt->execute()) {
    echo "<script>alert('Profile updated successfully'); window.location.href='patient-profile.php';</script>";
} else {
    echo "<script>alert('Something went wrong. Try again.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>