<?php
session_start();
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_id = $_POST['question_id'];
    $doctor_id = $_POST['doctor_id'];
    $answer = trim($_POST['answer']);

    if (!empty($question_id) && !empty($doctor_id) && !empty($answer)) {

        // Sanitize input
        $answer = mysqli_real_escape_string($conn, $answer);

        // Insert into answer table
        $insert_query = "INSERT INTO answer (question_id, doctor_id, answer) 
                         VALUES ('$question_id', '$doctor_id', '$answer')";

        if (mysqli_query($conn, $insert_query)) {
            // Update status to 'answered' in question table
            $update_query = "UPDATE question SET status = 'Answered' WHERE question_id = '$question_id'";
            mysqli_query($conn, $update_query);

            // Redirect back to view page
            header("Location: doctor-qna.php");
            exit();
        } else {
            echo "Error inserting answer: " . mysqli_error($conn);
        }

    } else {
        echo "All fields are required.";
    }
} else {
    echo "Invalid request method.";
}
?>
