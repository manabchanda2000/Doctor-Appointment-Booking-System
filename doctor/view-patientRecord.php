<?php
include '../connection.php';

if (isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];

    $query = "SELECT 
        a.*, 
        p.name AS patient_name, p.profile_img, p.gender AS patient_gender, p.phone AS patient_phone, p.email AS patient_email, p.blood_group, CONCAT(p.area, ', ', p.city, ', ', p.state, ' - ', p.pincode) AS patient_address,
        p.patient_id, TIMESTAMPDIFF(YEAR, p.dob, CURDATE()) AS age,
        d.name AS doctor_name, d.profile_img AS doctor_img, d.email AS doctor_email, d.phone AS doctor_phone, d.specialization, d.gender AS doctor_gender,
        cl.clinic_name, cl.phone AS clinic_phone, cl.email AS clinic_email, CONCAT(cl.area, ', ', cl.city, ', ', cl.state, ' - ', cl.pincode) AS clinic_address,
        t.amount, t.payment_mode, t.payment_status, t.transaction_date
        FROM appointment a
        JOIN patient p ON a.patient_id = p.patient_id
        JOIN doctor d ON a.doctor_id = d.doctor_id
        JOIN doctor_clinic cl ON a.clinic_id = cl.clinic_id
        LEFT JOIN transaction t ON t.appointment_id = a.appointment_id
        WHERE a.appointment_id = '$appointment_id'";

    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
?>
<!DOCTYPE html>
<html>

<head>
    <title>Appointment Details</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background: #f0f6ff;
        margin: 0;
        padding: 20px;
    }

    .details-container {
        max-width: 1000px;
        margin: auto;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px 30px;
    }

    h2,
    h3 {
        color: #0066cc;
        border-bottom: 1px solid #dce6f1;
        padding-bottom: 5px;
    }

    .section {
        margin: 20px 0;
    }

    .flex-row {
        display: flex;
        justify-content: space-between;
        gap: 40px;
    }

    .card {
        flex: 1;
    }

    .card img {
        border-radius: 50%;
        border: 3px solid #007bff;
        height: 80px;
        width: 80px;
    }

    p {
        margin: 10px 0;
    }
    </style>
</head>

<body>
    <div class="details-container">
        <h2>Appointment Details</h2>
        <div class="section">
            <p><strong>Appointment ID:</strong> <?php echo $row['appointment_id']; ?></p>
            <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></p>
            <p><strong>Time:</strong> <?php echo date('h:i A', strtotime($row['appointment_time'])); ?></p>
            <p><strong>Status:</strong> <?php echo $row['status']; ?></p>
            <p><strong>Doctor Confirmation:</strong> <?php echo $row['doctor_confirmation']; ?></p>
            <p><strong>Mode of Appointment:</strong> <?php echo $row['mode_of_appointment']; ?></p>
        </div>

        <div class="section flex-row">
            <div class="card">
                <h3>👨‍⚕️ Doctor Info</h3>
                <img src="../upload/doctor/<?php echo $row['doctor_img']; ?>" alt="Doctor Image">
                <p><strong>Name:</strong> <?php echo $row['doctor_name']; ?></p>
                <p><strong>Email:</strong> <?php echo $row['doctor_email']; ?></p>
                <p><strong>Phone:</strong> <?php echo $row['doctor_phone']; ?></p>
                <p><strong>Specialization:</strong> <?php echo $row['specialization']; ?></p>
                <p><strong>Gender:</strong> <?php echo $row['doctor_gender']; ?></p>
            </div>

            <div class="card">
                <h3>🧑 Patient Info</h3>
                <img src="../upload/patient/<?php echo $row['profile_img']; ?>" alt="Patient Image">
                <p><strong>Name:</strong> <?php echo $row['patient_name']; ?></p>
                <p><strong>Patient ID:</strong> <?php echo $row['patient_id']; ?></p>
                <p><strong>Phone:</strong> <?php echo $row['patient_phone']; ?></p>
                <p><strong>Email:</strong> <?php echo $row['patient_email']; ?></p>
                <p><strong>Gender/Age:</strong> <?php echo $row['patient_gender'] . ' / ' . $row['age']; ?></p>
                <p><strong>Address:</strong> <?php echo $row['patient_address']; ?></p>
                <p><strong>Blood Group:</strong> <?php echo $row['blood_group']; ?></p>
            </div>
        </div>

        <div class="section flex-row">
            <div class="card">
                <h3>🏥 Clinic Info</h3>
                <p><strong>Name:</strong> <?php echo $row['clinic_name']; ?></p>
                <p><strong>Address:</strong> <?php echo $row['clinic_address']; ?></p>
                <p><strong>Phone:</strong> <?php echo $row['clinic_phone']; ?></p>
                <p><strong>Email:</strong> <?php echo $row['clinic_email']; ?></p>
            </div>

            <div class="card">
                <h3>💰 Payment Info</h3>
                <p><strong>Amount:</strong> ₹<?php echo $row['amount']; ?></p>
                <p><strong>Payment Mode:</strong> <?php echo $row['payment_mode']; ?></p>
                <p><strong>Payment Status:</strong> <?php echo $row['payment_status']; ?></p>
                <p><strong>Transaction Date:</strong>
                    <?php echo $row['transaction_date'] ? date('M d, Y, h:i A', strtotime($row['transaction_date'])) : 'N/A'; ?>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
<?php
    } else {
        echo "No appointment found.";
    }
} else {
    echo "Invalid request.";
}
?>