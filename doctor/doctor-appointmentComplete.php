<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor-login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Step 1: Validate appointment and check confirmation status
    $checkQuery = "SELECT * FROM appointment 
                   WHERE appointment_id = '$appointment_id' 
                   AND doctor_id = '$doctor_id'";
    $result = mysqli_query($conn, $checkQuery);

    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['doctor_confirmation'] !== 'Approved') {
            echo "<script>
                    alert('This appointment is not approved yet. Cannot mark as completed.');
                    window.location.href = 'doctor-appointment.php';
                  </script>";
            exit();
        }
        if ($row['status'] === 'Completed') {
            echo "<script>
                    alert('This appointment is already Completed.');
                    window.location.href = 'doctor-appointment.php';
                  </script>";
            exit();
        }
        // Step 2: Update appointment status to Completed
        $updateAppointment = "UPDATE appointment 
                              SET status = 'Completed', payment_status = 'Paid'
                              WHERE appointment_id = '$appointment_id'";
        mysqli_query($conn, $updateAppointment);

        // Step 3: Update transaction payment status to Completed
        $updatePayment = "UPDATE transaction 
                          SET payment_status = 'Paid' 
                          WHERE appointment_id = '$appointment_id'";
        mysqli_query($conn, $updatePayment);

        echo "<script>
                alert('Appointment marked as completed and payment updated.');
                window.location.href = 'doctor-appointment.php';
              </script>";

    } else {
        echo "<script>
                alert('Invalid appointment or unauthorized access.');
                window.location.href = 'doctor-appointment.php';
              </script>";
    }
} else {
    echo "<script>
            alert('No appointment selected.');
            window.location.href = 'doctor-appointment.php';
          </script>";
}
?>
