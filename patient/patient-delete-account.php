<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: login.html");
    exit();
}

$patient_id = $_SESSION['patient_id'];

// Only proceed if the delete button is pressed
if (isset($_POST['delete_account'])) {

    // Optional: delete profile image file from folder
    $query = $conn->prepare("SELECT profile_img FROM patient WHERE patient_id = ?");
    $query->bind_param("i", $patient_id);
    $query->execute();
    $result = $query->get_result();
    if ($row = $result->fetch_assoc()) {
        $img_path = '../upload/patient/' . $row['profile_img'];
        if (file_exists($img_path)) {
            unlink($img_path); // delete the file
        }
    }
    $query->close();

    // Delete the account from DB
    $stmt = $conn->prepare("DELETE FROM patient WHERE patient_id = ?");
    $stmt->bind_param("i", $patient_id);

    if ($stmt->execute()) {
        session_destroy();
        echo "<script>alert('Account deleted successfully.'); window.location.href = '../index.php';</script>";
    } else {
        echo "<script>alert('Error deleting account. Try again.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    // Invalid access
    header("Location: patient-editProfile.php");
    exit();
}
?>
