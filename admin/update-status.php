<?php
session_start();
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $status = $_POST['availability'];



    // Update doctor availability in the database
    $sql = "UPDATE doctor SET availability = ? WHERE doctor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $doctor_id);

    if ($stmt->execute()) {
        echo "<script>alert('Doctor availability updated successfully!'); window.location.href='admin-manageDoctor.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method!";
}
?>
