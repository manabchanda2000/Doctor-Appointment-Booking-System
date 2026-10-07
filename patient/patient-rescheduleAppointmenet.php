<?php
session_start();
include '../connection.php';

if (!isset($_GET['id'])) {
    echo "Invalid Access";
    exit();
}

$appointment_id = $_GET['id'];

$query = "SELECT 
    a.appointment_id, a.appointment_date, a.appointment_time, a.mode_of_appointment, a.payment_mode, a.doctor_confirmation,
    d.doctor_id, d.name AS doctor_name, d.specialization, d.profile_img,
    c.clinic_id, c.clinic_name, c.area, c.city, c.state, c.pincode
FROM appointment a
JOIN doctor d ON a.doctor_id = d.doctor_id
JOIN doctor_clinic c ON a.clinic_id = c.clinic_id
WHERE a.appointment_id = '$appointment_id'
";


$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    echo "Appointment not found.";
    exit();
}

$row = mysqli_fetch_assoc($result);

if ($row['doctor_confirmation'] === 'Approved' || $row['doctor_confirmation'] === 'Rejected') {
    echo "<script>
        alert('You cannot reschedule this appointment because it is already {$row['doctor_confirmation']} by the doctor.');
        window.location.href = 'patient-appointment.php'; // change this to your actual list page
    </script>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reschedule Appointment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
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
            <img src="../upload/doctor/<?= $row['profile_img'] ?>" alt="Doctor">
            <div class="doctor-info">
                <h3><?= $row['doctor_name']; ?> (<?= $row['specialization']; ?>)</h3>
                <p><strong>Clinic:</strong> <?= $row['clinic_name']; ?></p>
                <p><strong>Address:</strong>
                    <?= $row['area'] . ', ' . $row['city'] . ', ' . $row['state'] . ' - ' . $row['pincode']; ?></p>
            </div>
        </div>

        <form action="patient-rescheduleAppointmenet-submit.php" method="POST">
            <!-- Hidden Fields -->
            <input type="hidden" name="appointment_id" value="<?= $appointment_id; ?>">
            <input type="hidden" name="doctor_id" value="<?= $row['doctor_id']; ?>">
            <input type="hidden" name="clinic_id" value="<?= $row['clinic_id']; ?>">

            <label for="appointment_date">Appointment Date</label>
            <input type="date" name="appointment_date" value="<?= $row['appointment_date']; ?>" required>

            <label for="appointment_time">Appointment Time</label>
            <input type="time" name="appointment_time" value="<?= $row['appointment_time']; ?>" required>

            <label>Mode of Appointment</label>
            <div class="radio-group">
                <label><input type="radio" name="mode_of_appointment" value="Physical"
                        <?= $row['mode_of_appointment'] === 'Physical' ? 'checked' : '' ?>> Physical</label>
                <label><input type="radio" name="mode_of_appointment" value="Video Call"
                        <?= $row['mode_of_appointment'] === 'Video Call' ? 'checked' : '' ?>> Video Call</label>
            </div>

            <label>Payment Mode</label>
            <div class="radio-group">
                <label><input type="radio" name="payment_mode" value="Online"
                        <?= $row['payment_mode'] === 'Online' ? 'checked' : '' ?>> Online</label>
                <label><input type="radio" name="payment_mode" value="Offline"
                        <?= $row['payment_mode'] === 'Offline' ? 'checked' : '' ?>> Offline</label>
            </div>

            <button type="submit" class="submit-btn">Update Appointment</button>
        </form>
    </div>

</body>

</html>