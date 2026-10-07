<?php
session_start();
include '../connection.php'; // DB connection file

if (isset($_GET['question_id'])) {
    $question_id = intval($_GET['question_id']);
    
    $check = mysqli_query($conn, "SELECT * FROM question WHERE question_id = '$question_id'");

    if (mysqli_num_rows($check) > 0) {
        $delete = mysqli_query($conn, "DELETE FROM question WHERE question_id = '$question_id'");
        if ($delete) {
            echo "<script>alert('Successfully Deleted!'); window.history.back();</script>";
            exit();
        } else {
            echo "Failed to delete the question.";
        }
    } else {
        echo "You are not authorized to delete this question.";
    }
} else {
    echo "Invalid Request.";
}
?>
