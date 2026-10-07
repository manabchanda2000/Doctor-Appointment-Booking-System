<?php
include "../connection.php";

// Get form data
$full_name = trim($_POST['name']);
$license_no = trim($_POST['UID']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$gender = trim($_POST['gender']);

// Check if email or license number already exists
$sql_check = "SELECT * FROM doctor_request WHERE email = ? OR UID = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ss", $email, $license_no);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['email'] == $email) {
        echo "<script>alert('❌ This email is already registered! Try another or login.'); window.history.back();</script>";
    } elseif ($row['UID'] == $license_no) {
        echo "<script>alert('❌ This UID number is already registered! Contact support if this is an error.'); window.history.back();</script>";
    }
    exit;
}

// Insert data into database
$sql_insert = "INSERT INTO doctor_request (name, UID, email, phone, gender) VALUES (?, ?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("sssss", $full_name, $license_no, $email, $phone, $gender);

if ($stmt_insert->execute()) {
    echo "<script>alert('✅ Registration successful! You will get a mail after verification'); window.location.href = '../index.php';</script>";
} else {
    echo "<script>alert('❌ Registration failed! Try again later.'); window.history.back();</script>";
}

// Close connections
$stmt_check->close();
$stmt_insert->close();
$conn->close();
?>
