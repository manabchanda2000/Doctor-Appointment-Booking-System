<?php
session_start();
include '../connection.php';

if (isset($_GET['did']) && isset($_GET['cid'])) {
    $doctor_id = $_GET['did'];
    $clinic_id = $_GET['cid'];
} else {
    header("Location: patient-appointment.php");
    exit();
}

// Doctor info
$doc_q = mysqli_query($conn, "SELECT * FROM doctor WHERE doctor_id='$doctor_id'");
$doctor = mysqli_fetch_assoc($doc_q);

// Clinic info
$clinic_q = mysqli_query($conn, "SELECT * FROM doctor_clinic WHERE clinic_id='$clinic_id'");
$clinic = mysqli_fetch_assoc($clinic_q);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
    * {
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: #f0f6ff;
        padding: 40px 20px;
    }

    .form-wrapper {
        max-width: 700px;
        margin: auto;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 123, 255, 0.1);
        padding: 30px;
    }

    .doctor-details {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 20px;
    }

    .doctor-details img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #007bff;
    }

    .doctor-info {
        flex: 1;
    }

    .doctor-info h3 {
        margin-bottom: 4px;
        color: #007bff;
    }

    .doctor-info p {
        margin-bottom: 3px;
        color: #444;
    }

    form label {
        display: block;
        margin: 16px 0 6px;
        font-weight: 600;
        color: #333;
    }

    form input[type="date"],
    form input[type="time"] {
        width: 100%;
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #ccc;
        font-size: 16px;
    }

    .radio-group {
        display: flex;
        gap: 20px;
        margin-top: 6px;
    }

    .radio-group label {
        font-weight: normal;
    }

    .submit-btn {
        margin-top: 25px;
        width: 100%;
        padding: 14px;
        background-color: #007bff;
        color: white;
        font-size: 18px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.3s ease;
    }

    .submit-btn:hover {
        background-color: #0056b3;
    }
    </style>
</head>

<body>

    <div class="form-wrapper">
        <div class="doctor-details">
            <img src="../upload/doctor/<?= $doctor['profile_img'] ?>" alt="Doctor">
            <div class="doctor-info">
                <h3><?php echo $doctor['name']; ?> (<?php echo $doctor['specialization']; ?>)</h3>
                <p><strong>Clinic:</strong> <?php echo $clinic['clinic_name']; ?></p>
                <p><strong>Address:</strong>
                    <?php echo $clinic['area'] . ', ' . $clinic['city'] . ', ' . $clinic['state'] . ' - ' . $clinic['pincode']; ?>
                </p>
            </div>
        </div>

        <form action="patient-bookAppointment-submit.php" method="POST">
            <!-- Hidden doctor/clinic ID -->
            <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
            <input type="hidden" name="clinic_id" value="<?php echo $clinic_id; ?>">

            <label for="appointment_date">Appointment Date</label>
            <input type="date" name="appointment_date" required>

            <label for="appointment_time">Appointment Time</label>
            <input type="time" name="appointment_time" required>

            <label>Mode of Appointment</label>
            <div class="radio-group">
                <label><input type="radio" name="mode_of_appointment" value="Physical" required> Physical</label>
                <label><input type="radio" name="mode_of_appointment" value="Video Call"> Video Call</label>
            </div>

            <label>Payment Mode</label>
            <div class="radio-group">
                <label><input type="radio" name="payment_mode" value="Online" required> Online</label>
                <label><input type="radio" name="payment_mode" value="Offline"> Offline</label>
            </div>

            <button type="submit" class="submit-btn">Confirm Appointment</button>
        </form>
    </div>

</body>

</html>