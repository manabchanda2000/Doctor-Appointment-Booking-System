<?php
session_start();
include '../connection.php';

if (isset($_GET['doctor_id'])) {
    $doctor_id = $_GET['doctor_id'];

    // Delete doctor from database
    $sql = "DELETE FROM doctor WHERE doctor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $doctor_id);

    if ($stmt->execute()) {
        echo "<script>alert('Doctor deleted successfully!'); window.location.href='admin-manageDoctor.php';</script>";
    } else {
        echo "<script>alert('Error deleting doctor!'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request!'); window.history.back();</script>";
}
?>
