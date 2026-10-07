<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.html");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

// Sanitize and collect inputs
$name         = trim($_POST['name']);
$email        = trim($_POST['email']);
$phone        = trim($_POST['phone']);
$dob          = $_POST['dob'];
$gender       = $_POST['gender'] ?? null;
$address      = trim($_POST['address']);
$special      = $_POST['specialization'];
$experience   = trim($_POST['experience']);
$qualification = trim($_POST['qualification']);
$fees         = $_POST['fees'] ?? null;
$emergency    = $_POST['emergency'] ?? 'no';
$bio          = trim($_POST['bio']);

// --- Handle profile image upload ---
$img_name = null;
if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
    $img_tmp  = $_FILES['profile_img']['tmp_name'];
    $img_ext  = pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION);
    $img_name = 'doctor_' . time() . '.' . $img_ext;
    move_uploaded_file($img_tmp, '../upload/doctor/' . $img_name);
}

// --- Update Query ---
$sql = "UPDATE doctor SET 
            name = ?, email = ?, phone = ?, dob = ?, gender = ?, address = ?, 
            specialization = ?, experience = ?, qualification = ?, fees = ?, 
            emergency = ?, bio = ?, updated_at = NOW()";

if ($img_name) {
    $sql .= ", profile_img = ?";
}
$sql .= " WHERE doctor_id = ?";

// ✅ Prepare Statement
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// ✅ Bind Parameters
if ($img_name) {
    $stmt->bind_param("sssssssssdsssi", $name, $email, $phone, $dob, $gender, $address,
        $special, $experience, $qualification, $fees, $emergency, $bio, $img_name, $doctor_id);
} else {
    $stmt->bind_param("sssssssssdssi", $name, $email, $phone, $dob, $gender, $address,
        $special, $experience, $qualification, $fees, $emergency, $bio, $doctor_id);
}

// ✅ Execute and Check
if ($stmt->execute()) {
    echo "<script>alert('Profile updated successfully'); window.location.href='doctor-profile.php';</script>";
} else {
    echo "<script>alert('Something went wrong. Try again.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
