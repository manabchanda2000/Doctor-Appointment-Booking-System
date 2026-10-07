<?php
session_start();
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $appointment_id = $_POST['appointment_id'];
    $doctor_id = $_POST['doctor_id'];
    $clinic_id = $_POST['clinic_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $mode_of_appointment = $_POST['mode_of_appointment'];
    $payment_mode = $_POST['payment_mode'];

    $update_query = "UPDATE appointment SET 
        appointment_date = '$appointment_date',
        appointment_time = '$appointment_time',
        mode_of_appointment = '$mode_of_appointment',
        payment_mode = '$payment_mode',
        status = 'Scheduled',
        doctor_confirmation = 'Pending',
        payment_status = 'Pending'
    WHERE appointment_id = '$appointment_id'";

    if (mysqli_query($conn, $update_query)) {
        // Redirect to appointment list or confirmation
        header("Location: patient-appointment.php");
        exit();
    } else {
        echo "Error updating appointment: " . mysqli_error($conn);
    }
} else {
    echo "Invalid Request.";
}
?>
