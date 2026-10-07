<?php
session_start();
include '../connection.php'; // database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $question_id = intval($_POST['question_id']);
    $category = mysqli_real_escape_string($conn, $_POST['specialty']);
    $title = mysqli_real_escape_string($conn, $_POST['question']);
    $description = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : '';


    // Update query
    $sql = "UPDATE question SET 
                specialty = '$category', 
                question = '$title', description= '$description' 
            WHERE question_id = $question_id 
            AND patient_id = {$_SESSION['patient_id']}";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Question Updated successfully!'); window.location.href='patient-qna.php';</script>";
    } else {
        $_SESSION['msg'] = "Error updating question: " . mysqli_error($conn);
    }

    header("Location: patient-qna.php"); // redirect back to qna page
    exit();
}
?>
