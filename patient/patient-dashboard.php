<?php
session_start();
// If user is not logged in, redirect to login
if (!isset($_SESSION['patient_id'])) {
    header("Location: patient-login.html");
    exit();
}
include '../connection.php';
$patient_id=$_SESSION['patient_id']??0;

//patient details for 1st section
$sql = "SELECT * FROM patient WHERE patient_id = '$patient_id'";
$result = mysqli_query($conn, $sql);
if ($result->num_rows === 1) {
    $user = mysqli_fetch_assoc($result);
}

// Default values
$total_appointments = 0;
$total_paid = 0.00;
$total_reviews = 0;
$total_questions = 0;

 // Total Appointments
 $stmt = $conn->prepare("SELECT COUNT(*) FROM appointment WHERE patient_id = ? AND status = 'completed'");
 $stmt->bind_param("i", $patient_id);
 $stmt->execute();
 $stmt->bind_result($total_appointments);
 $stmt->fetch();
 $stmt->close();

 // Total Paid Amount
 $stmt = $conn->prepare("SELECT SUM(amount) FROM transaction WHERE patient_id = ? AND payment_status = 'Paid'");
 $stmt->bind_param("i", $patient_id);
 $stmt->execute();
 $stmt->bind_result($sum_paid);
 $stmt->fetch();
 $total_paid = $sum_paid !== null ? $sum_paid : 0.00;
 $stmt->close();

 // Last Login
 $stmt = $conn->prepare("SELECT logout_time FROM log_info WHERE patient_id = ? AND status = 'Success' ORDER BY logout_time DESC LIMIT 1");
 $stmt->bind_param("i", $patient_id);
 $stmt->execute();
 $stmt->bind_result($logout_time);
 if ($stmt->fetch() && !empty($logout_time)) {
     $last_active = date('d M Y, h:i A', strtotime($logout_time));
 } else {
     $last_active = 'New Patient';
 }
 $stmt->close();

 // Total Reviews
 $stmt = $conn->prepare("SELECT COUNT(*) FROM review WHERE patient_id = ?");
 $stmt->bind_param("i", $patient_id);
 $stmt->execute();
 $stmt->bind_result($total_reviews);
 $stmt->fetch();
 $stmt->close();

 // Total Questions Asked
 $stmt = $conn->prepare("SELECT COUNT(*) FROM question WHERE patient_id = ?");
 $stmt->bind_param("i", $patient_id);
 $stmt->execute();
 $stmt->bind_result($total_questions);
 $stmt->fetch();
 $stmt->close();   

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    main {
        background: linear-gradient(135deg, #f2f8fc, #f0f8ff);
        padding: 80px 20px 20px 280px;
    }

    /* first Section */
    .container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(to left, rgb(222, 190, 247), rgb(146, 203, 206));
        padding: 20px;
        border-radius: 12px;
        /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); */
        margin-bottom: 30px;
    }

    .left-container {
        width: 70%;
    }

    .container h2 {
        font-size: 2em;
        margin-bottom: 20px;
    }

    .right-container {
        display: flex;
        align-items: center;
        justify-content: right;
        flex-wrap: wrap;
        gap: 10px;
    }

    .button {
        flex: 0 0 220px;
        background-color: #3B82F6;
        padding: 12px 24px;
        border-radius: 5px;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;

    }

    .emergency-help {
        background-color: #d9534f;
    }

    .button:hover {
        opacity: 0.9;
    }

    /* second section----------------------- */
    .second-sec {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .count-box {
        flex: 0 0 250px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        box-shadow: 2px 4px 4px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .count-box div {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .count-box span {
        height: 40px;
        width: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .count-box h3 {
        font-size: 12px;
    }

    .count-box strong {
        font-size: 18px;
    }

    .box1 {
        background: linear-gradient(135deg, #EFF6FF -4%, #DBEAFE 100%);
    }

    .box1 span {
        background-color: #b3d2dc;
    }

    .box2 {
        background: linear-gradient(135deg, #FEFCE8 -4%, #FEF9C3 100%);
    }

    .box2 span {
        background-color: #FEF08A;
        color: #CA8A04;
    }

    .box3 {
        background: linear-gradient(135deg, #F0FDF4 -4%, #DCFCE7 100%);
    }

    .box3 span {
        background-color: #BBF7D0;
        color: #16A34A;
    }

    .box4 {
        background: linear-gradient(135deg, #FAF5FF -4%, #F3E8FF 100%);
    }

    .box4 span {
        background-color: #E9D5FF;
        color: #9333EA;
    }

    .box5 {
        background: linear-gradient(135deg, #e3e1fb -4%, #bfbdfa 100%);
    }

    .box5 span {
        background-color: #c6baf9;
        color: #7033ea;
    }

    /* third section-------------------- */

    .third-sec {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .activity-box {
        flex: 0 0 580px;
        background-color: #fdfdfd;
        border-radius: 10px;
        box-shadow: 2px 4px 6px rgba(0, 0, 0, 0.15);
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        transition: 0.3s;
    }

    .box-top {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;

    }

    .box-top h2 {
        font-size: 20px;
    }

    #viewAllBtn {
        color: #2563EB;
        transition: 0.3s;
        text-decoration: none;

    }

    #viewAllBtn:hover {
        font-weight: bolder;
    }

    .box-data {
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.15);
        padding: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        margin-bottom: 10px;
        border-radius: 5px;
        transition: all .3s ease;
        border: 1px solid transparent;
    }

    .box-data:hover {
        transform: translateY(-5px);
        border: 1px solid #b3d2dc;
    }

    .box-data img {
        height: 40px;
        width: 40px;
        border-radius: 50%;
    }

    .box-data h3 {
        font-size: 16px;
        color: #111827;
    }

    .box-data p {
        font-size: 14px;
        color: #6B7280;
    }

    .box-data span {
        font-size: 12px;
        color: #6B7280;
        margin-right: 10px;
    }

    .status {
        padding: 6px 14px;
        border-radius: 12px;
        color: #15803D;
        font-size: 14px;
        background-color: #F0FDF4;
    }

    .download-icon {
        font-size: 16px;
        color: #007bff;
        text-decoration: none;
        transition: 0.3s;
        margin-right: 15px;
    }

    .download-icon:hover {
        font-weight: bolder;
    }

    /* forth section--------------------------------- */
    .forth-sec {
        padding: 20px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }

    .box-forth {
        flex: 0 0 360px;
        background: linear-gradient(135deg, #EFF6FF -2%, #F5F3FF 100%);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        border-radius: 10px;
        padding: 0 15px;
    }

    .box-forth-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        width: 100%;
    }

    .box-forth-top h2 {
        font-size: 20px;
    }

    .box-forth-top a {
        text-decoration: none;
        color: #2563EB;
        padding: 6px 12px;
        border: 1px solid #2563EB;
        border-radius: 5px;
        font-size: 14px;
        transition: all .3s ease;
    }

    .box-forth-top a:hover {
        background-color: #2563EB;
        color: #ffffff;
    }

    .tips {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        padding: 15px 5px;
        border-bottom: 1px solid #c6cbd8;
        margin-bottom: 20px;
        width: 100%;
        transition: all .3s ease;
    }

    .tips:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
    }

    .tips h3 {
        font-size: 16px;
    }

    .tips p {
        font-size: 14px;
        line-height: 1.4;
        color: #6B7280;
        text-align: justify;
    }

    .tips a {
        text-decoration: none;
        color: #2563EB;
        font-size: 14px;
        transition: all .3s ease;
    }

    .box-forth-data {
        display: flex;
        align-items: center;
        padding: 15px;
        gap: 10px;
        width: 100%;
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        margin-bottom: 20px;
        transition: all .3s ease;
    }

    .box-forth-data:hover {
        scale: 1.02;
        border: 1px solid #b3d2dc;
    }

    .box-forth-data img {
        height: 40px;
        width: 40px;
        border-radius: 50%;
        object-fit: fill;
    }

    .box-forth-data div {
        flex: 1;
    }

    .box-forth-data div h3 {
        font-size: 14px;
    }

    .box-forth-data div p {
        font-size: 12px;
        color: #6B7280;
    }

    .box-forth-data span {
        padding: 5px 10px;
        border-radius: 8px;
        background-color: #DCFCE7;
        color: #166534;
        font-size: 12px;
    }
    </style>
</head>

<body>
    <?php include 'patient-header.php';?>
    <main>
        <section class="first-sec">
            <div class="container">
                <div class="left-container">
                    <h2>Welcome back, <?= htmlspecialchars($user['name'] ?? 'Guest') ?>!</h2>
                    <strong>Last Active, <?= $last_active ?></strong>
                </div>
                <div class="right-container">
                    <a href="patient-editProfile.php" class="button">👨‍⚕️ Edit Profile</a>
                    <a href="patient-finddoctor.php" class="button">📅 Book Appointment</a>
                    <a href="patient-emergency.php" class="button emergency-help">🚑 Emergency Help</a>
                    <a href="patient-medicalRecord.php" class="button">📋 Medical Records</a>
                </div>
            </div>
        </section>

        <section class="second-sec">
            <div class="count-box box1">
                <div>
                    <span><i class="fas fa-calendar-check text-custom text-xl"></i></span>
                    <strong><?= $total_appointments ?></strong>
                </div>
                <h3>Total Appointments</h3>
            </div>

            <div class="count-box box2">
                <div>
                    <span><i class="fa-solid fa-money-bill"></i></span>
                    <strong><?= $total_paid ?></strong>
                </div>
                <h3>Total Paid</h3>
            </div>

            <div class="count-box box4">
                <div>
                    <span><i class="fas fa-star text-purple-600 text-xl"></i></span>
                    <strong><?= $total_reviews ?></strong>
                </div>
                <h3>Reviews Given</h3>
            </div>

            <div class="count-box box5">
                <div>
                    <span><i class="fa-solid fa-question"></i></span>
                    <strong><?= $total_questions ?></strong>
                </div>
                <h3>Ask Questions</h3>
            </div>
        </section>

        <section class="third-sec">

            <!-- Appointments Section -->
            <div class="appointments activity-box">
                <div class="box-top">
                    <h2>Upcoming Appointments</h2>
                    <a href="patient-appointment.php" id="viewAllBtn">View All</a>
                </div>

                <?php
        $stmt = $conn->prepare("
            SELECT a.*, d.name AS doctor_name, d.specialization, d.profile_img, dc.clinic_name
            FROM appointment a
            JOIN doctor d ON a.doctor_id = d.doctor_id
            JOIN doctor_clinic dc ON a.clinic_id = dc.clinic_id
            WHERE a.patient_id = ? AND a.status = 'Scheduled'
            ORDER BY a.created_at DESC
        ");
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
                <div class="box-data">
                    <div><img src="../upload/doctor/<?php echo $row['profile_img'] ?? 'user.jpg'; ?>" alt="doc">
                    </div>
                    <div style="flex: 1;">
                        <h3><?php echo htmlspecialchars($row['doctor_name']); ?></h3>
                        <p><?php echo htmlspecialchars($row['specialization']); ?></p>
                        <span><i class="fa-regular fa-clock"></i>
                            <?php echo date('M d, Y,', strtotime($row['appointment_date'])) . ', ' . date('h:i A', strtotime($row['appointment_time'])); ?>
                        </span>
                        <span><i class="fas fa-hospital"></i>
                            <?php echo htmlspecialchars($row['clinic_name']); ?></span>
                    </div>
                    <data class="status">Scheduled</data>
                </div>
                <?php endwhile; else: ?>
                <div class="box-data text-muted">No Scheduled appointments.</div>
                <?php endif; ?>
            </div>

            <!-- Prescriptions Section -->
            <div class="prescriptions activity-box">
                <div class="box-top">
                    <h2>Recent Prescriptions</h2>
                    <a href="patient-medicalRecord.php" id="viewAllBtn">View All</a>
                </div>

                <?php
        $stmt = $conn->prepare("
            SELECT p.*, d.name AS doctor_name 
            FROM prescription p
            JOIN doctor d ON p.doctor_id = d.doctor_id
            WHERE p.patient_id = ?
            ORDER BY p.created_at DESC
            LIMIT 3
        ");
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
                <div class="box-data">
                    <div style="flex: 1;">
                        <h3>Prescribed by <?php echo htmlspecialchars($row['doctor_name']); ?></h3>
                        <span><i class="fas fa-calendar-check text-custom text-xl"></i> Issued:
                            <?php echo date('M d, Y', strtotime($row['created_at'])); ?> </span>
                    </div>
                    <?php if (!empty($row['prescription'])): ?>
                    <a href="../upload/patient/<?php echo $row['prescription']; ?>" download class="download-icon">
                        <i class="fa-solid fa-download"></i>
                    </a>
                    <?php else: ?>
                    <span class="text-muted">No file</span>
                    <?php endif; ?>
                </div>
                <?php endwhile; else: ?>
                <div class="box-data text-muted">No prescriptions found.</div>
                <?php endif; ?>
            </div>

        </section>


        <section class="forth-sec">

            <!-- Health Tips -->
            <div class="box-forth">
                <div class="box-forth-top">
                    <h2>Health Tips</h2>
                    <a href="patient-healthtips.php">View All</a>
                </div>

                <?php
        $stmt = $conn->prepare("SELECT * FROM health_tips ORDER BY created_at DESC LIMIT 3");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0):
            while ($tip = $result->fetch_assoc()):
        ?>
                <div class="tips">
                    <h3><?php echo htmlspecialchars($tip['title']); ?></h3>
                    <p><?php echo htmlspecialchars(substr($tip['description'], 0, 100)); ?>...</p>
                    <a href="patient-tipsDetails.php?tip_id=<?= $tip['tip_id'] ?>">Read More</a>
                </div>
                <?php endwhile; else: ?>
                <div class="tips text-muted">No health tips found.</div>
                <?php endif; ?>
            </div>

            <!-- Emergency Booking -->
            <div class="box-forth">
                <div class="box-forth-top">
                    <h2>Emergency Booking</h2>
                    <a href="">View All</a>
                </div>

                <?php
        $stmt = $conn->prepare("
            SELECT e.*, d.name AS doctor_name, d.profile_img 
            FROM emergency_booking e 
            JOIN doctor d ON e.doctor_id = d.doctor_id 
            WHERE e.patient_id = ? AND e.status = 'Completed'
            ORDER BY e.requested_at DESC 
            LIMIT 3
        ");
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
                <div class="box-forth-data emergency">
                    <img src="../upload/doctor/<?php echo $row['profile_img'] ?? 'user.jpg'; ?>" alt="doc">
                    <div>
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><?php echo date('d M, h:i A', strtotime($row['requested_at'])); ?>
                        </p>
                    </div>
                    <span>Completed</span>
                </div>
                <?php endwhile; else: ?>
                <div class="box-forth-data text-muted">No emergency bookings.</div>
                <?php endif; ?>
            </div>

            <!-- Payments -->
            <div class="box-forth">
                <div class="box-forth-top">
                    <h2>Payments</h2>
                    <a href="patient-payment.php">View All</a>
                </div>

                <?php
        $stmt = $conn->prepare("
            SELECT p.*, d.name AS doctor_name, d.profile_img 
            FROM transaction p 
            JOIN doctor d ON p.doctor_id = d.doctor_id 
            WHERE p.patient_id = ? 
            ORDER BY p.transaction_date DESC 
            LIMIT 3
        ");
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
                <div class="box-forth-data payments">
                    <img src="../upload/doctor/<?php echo $row['profile_img'] ?? 'user.jpg'; ?>" alt="doc">
                    <div>
                        <h3><?php echo htmlspecialchars($row['doctor_name']); ?></h3>
                        <p><?php echo date('M d Y, h:i A', strtotime($row['transaction_date'])); ?>
                        </p>
                    </div>
                    <data>₹<?php echo number_format($row['amount'], 2); ?></data>
                </div>
                <?php endwhile; else: ?>
                <div class="box-forth-data text-muted">No payments found.</div>
                <?php endif; ?>
            </div>

        </section>

    </main>
    <?php include 'patient-footer.php'; ?>
</body>

</html>