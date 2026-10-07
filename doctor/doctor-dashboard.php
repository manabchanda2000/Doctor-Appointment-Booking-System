<?php
session_start();
// If user is not logged in, redirect to login
if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor-login.html");
    exit();
}
include '../connection.php';
$doctor_id=$_SESSION['doctor_id']??0;

//doctor details for 1st section
$sql = "SELECT * FROM doctor WHERE doctor_id = '$doctor_id'";
$result = mysqli_query($conn, $sql);
if ($result->num_rows === 1) {
    $user = mysqli_fetch_assoc($result);
}

// Default values
$total_appointments = 0;
$total_earnings = 0.00;
$last_login = 'N/A';
$total_patients = 0;
$average_rating = 0;
$total_earnings = 0;

// Total Appointments (Completed)
$appointment_sql = "SELECT COUNT(*) AS total_appointments 
                    FROM appointment 
                    WHERE doctor_id = '$doctor_id' 
                    AND status = 'completed'";
$appointment_result = mysqli_query($conn, $appointment_sql);
$appointment_data = mysqli_fetch_assoc($appointment_result);
$total_appointments = $appointment_data['total_appointments'] ?? 0;

// Total Earnings (Paid)
$earning_sql = "SELECT SUM(amount) AS total_earnings 
                FROM transaction 
                WHERE doctor_id = '$doctor_id' 
                AND payment_status = 'paid'";
$earning_result = mysqli_query($conn, $earning_sql);
$earning_data = mysqli_fetch_assoc($earning_result);
$total_earnings = $earning_data['total_earnings'] ?? 0;

// Total Unique Patients
$patient_sql = "SELECT COUNT(DISTINCT patient_id) AS total_patients 
                FROM appointment 
                WHERE doctor_id = '$doctor_id'";
$patient_result = mysqli_query($conn, $patient_sql);
$patient_data = mysqli_fetch_assoc($patient_result);
$total_patients = $patient_data['total_patients'] ?? 0;

// Average Reviews (From doctor table)
$doctor_sql = "SELECT rating FROM doctor WHERE doctor_id = '$doctor_id'";
$doctor_result = mysqli_query($conn, $doctor_sql);
$doctor_data = mysqli_fetch_assoc($doctor_result);
$average_rating = $doctor_data['rating'] ?? 0;

// Total Emergency Cases
$emergency_sql = "SELECT COUNT(*) AS total_emergency 
                  FROM emergency_booking 
                  WHERE doctor_id = '$doctor_id' 
                  AND status = 'completed'";
$emergency_result = mysqli_query($conn, $emergency_sql);
$emergency_data = mysqli_fetch_assoc($emergency_result);
$total_emergency = $emergency_data['total_emergency'] ?? 0;

 // Last Login
 $stmt = $conn->prepare("SELECT logout_time FROM log_info WHERE doctor_id = ? AND status = 'Success' ORDER BY logout_time DESC LIMIT 1");
 $stmt->bind_param("i", $doctor_id);
 $stmt->execute();
 $stmt->bind_result($logout_time);
 if ($stmt->fetch() && !empty($logout_time)) {
     $last_active = date('d M Y, h:i A', strtotime($logout_time));
 } else {
     $last_active = 'New Doctor';
 }
 $stmt->close();
 

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
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
        flex: 0 0 210px;
        padding: 20px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        box-shadow: 2px 4px 4px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .count-box div {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
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
        font-size: 16px;
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
        background: linear-gradient(135deg, #ffe9e9 -4%, #ffc0c0 100%);
    }

    .box5 span {
        background-color: #ffa5a5;
        color: rgb(235, 52, 52);
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

    .tips h3,
    .qna h3 {
        font-size: 16px;
    }

    .tips p {
        font-size: 14px;
        line-height: 1.4;
        color: #6B7280;
        text-align: justify;
    }

    .tips #review-top,
    #qna-top {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .tips img,
    #qna-top img {
        height: 30px;
        width: 30px;
        border-radius: 50%;
        object-fit: fill;
    }

    .tips #rating {
        text-decoration: none;
        color: #2563EB;
        font-size: 14px;
        transition: all .3s ease;
    }

    .qna {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 100%;
        margin-bottom: 20px;
        padding: 10px;
        border-bottom: 1px solid #c6cbd8;
        transition: all .3s ease;
    }

    #question {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    #question h4 {
        font-size: 14px;
    }

    #question #ans {
        text-decoration: none;
        color: black;
        padding: 5px;
        background-color: #b3d2dc;
        border-radius: 5px;
        font-size: 12px;
    }

    .qna:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
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
    <?php include 'doctor-header.php';?>
    <main>
        <section class="first-sec">
            <div class="container">
                <div class="left-container">
                    <h2>Welcome back, <?= htmlspecialchars($user['name'] ?? 'Guest') ?>!</h2>
                    <strong>Last Active: <?= $last_active ?></strong>
                </div>
                <div class="right-container">
                    <a href="doctor-finddoctor.php" class="button">➕ Add Prescription</a>
                    <a href="doctor-appointment.php" class="button">✍️ Health Tips</a>
                    <a href="doctor-emergency.php" class="button emergency-help">🆘 Emergency</a>
                    <a href="doctor-medicalRecord.php" class="button">📋 Announcement</a>
                </div>
            </div>
        </section>

        <section class="second-sec">
            <div class="count-box box1">
                <span><i class="fas fa-calendar-check text-custom text-xl"></i></span>
                <div>
                    <h3>Total Appointments</h3>
                    <strong><?= $total_appointments ?></strong>
                </div>
            </div>

            <div class="count-box box2">
                <span><i class="fa-solid fa-money-bill"></i></span>
                <div>
                    <h3>Total Earnings</h3>
                    <strong><?= $total_earnings ?></strong>
                </div>
            </div>

            <div class="count-box box3">
                <span><i class="fa-solid fa-hospital-user"></i></span>
                <div>
                    <h3>Total Patients</h3>
                    <strong><?= $total_patients ?></strong>
                </div>
            </div>

            <div class="count-box box4">
                <span><i class="fas fa-star text-purple-600 text-xl"></i></span>
                <div>
                    <h3>Avg. Reviews</h3>
                    <strong><?= number_format($average_rating, 1) ?></strong>
                </div>
            </div>

            <div class="count-box box5">
                <span><i class="fa-solid fa-truck-medical"></i></span>
                <div>
                    <h3>Emergency</h3>
                    <strong><?= $total_emergency ?></strong>
                </div>
            </div>
        </section>


        <section class="third-sec">

            <!-- Appointments Section -->
            <div class="appointments activity-box">
                <div class="box-top">
                    <h2>Upcoming Appointments</h2>
                    <a href="doctor-appointment.php" id="viewAllBtn">View All</a>
                </div>
                <?php
                $upcoming_query = "SELECT a.*, p.name AS patient_name, p.profile_img, dc.clinic_name
                FROM appointment a
                JOIN patient p ON a.patient_id = p.patient_id
                JOIN doctor_clinic dc ON a.clinic_id = dc.clinic_id
                WHERE a.doctor_id = '$doctor_id' 
                AND a.status = 'Scheduled' 
                ORDER BY a.appointment_date ASC
                LIMIT 5";
                $upcoming_result = mysqli_query($conn, $upcoming_query);
                ?>
                <?php if (mysqli_num_rows($upcoming_result) > 0): ?>
                <?php while($appointment = mysqli_fetch_assoc($upcoming_result)): ?>
                <div class="box-data">
                    <div><img src="../upload/patient/<?= $appointment['profile_img'] ?? '../img/user.jpg' ?>"
                            alt="patient">
                    </div>
                    <div style="flex: 1;">
                        <h3><?= htmlspecialchars($appointment['patient_name']) ?></h3>
                        <span><i class="fa-regular fa-clock"></i>
                            <?= date('M d, Y, h:i A', strtotime($appointment['appointment_date'].' '.$appointment['appointment_time'])) ?></span>
                        <span><i class="fas fa-hospital"></i>
                            <?= htmlspecialchars($appointment['clinic_name'] ?? 'N/A') ?></span>
                    </div>
                    <data class="status">Scheduled</data>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <div class="box-data text-muted">No Scheduled appointments.</div>
                <?php endif; ?>

            </div>

            <!-- Recent Emergency Section -->
            <div class="prescriptions activity-box">
                <div class="box-top">
                    <h2>Emergency</h2>
                    <a href="emergency-appointments.php" id="viewAllBtn">View All</a>
                </div>
                <?php
                    // $emergency_query = "SELECT a.*, p.name AS patient_name, p.profile_image
                    // FROM appointments a
                    // JOIN patient p ON a.patient_id = p.patient_id
                    // WHERE a.doctor_id = '$doctor_id' 
                    // AND a.status = 'pending' 
                    // AND a.appointment_type = 'emergency' 
                    // ORDER BY a.appointment_date ASC
                    // LIMIT 5";
                    // $emergency_result = mysqli_query($conn, $emergency_query);
                    ?>
                <?php if (mysqli_num_rows($emergency_result) > 0): ?>
                <?php while($emergency = mysqli_fetch_assoc($emergency_result)): ?>
                <div class="box-data">
                    <div><img src="<?= $emergency['profile_image'] ?? '../img/default-patient.png' ?>" alt="patient">
                    </div>
                    <div style="flex: 1;">
                        <h3><?= htmlspecialchars($emergency['patient_name']) ?></h3>
                        <p><?= htmlspecialchars($emergency['problem'] ?? 'Emergency Case') ?></p>
                        <span><i class="fa-regular fa-clock"></i>
                            <?= date('M d, Y h:i A', strtotime($emergency['appointment_date'].' '.$emergency['appointment_time'])) ?></span>
                        <span><i class="fa-solid fa-location-dot"></i>
                            <?= htmlspecialchars($emergency['location'] ?? 'N/A') ?></span>
                    </div>
                    <data class="status"><?= ucfirst($emergency['status']) ?></data>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <p style="padding: 15px;">No Emergency Cases</p>
                <?php endif; ?>

            </div>

        </section>



        <section class="forth-sec">
            <!-- Latest Reviews -->
            <div class="box-forth">
                <div class="box-forth-top">
                    <h2>Latest Reviews</h2>
                    <a href="doctor-reviews.php">View All</a>
                </div>
                <?php
                    $review_query = "SELECT r.*, p.name AS patient_name, p.profile_img 
                    FROM review r
                    JOIN patient p ON r.patient_id = p.patient_id
                    WHERE r.doctor_id = '$doctor_id'
                    ORDER BY r.review_date DESC
                    LIMIT 3";
                    $review_result = mysqli_query($conn, $review_query);
                    ?>
                <?php if (mysqli_num_rows($review_result) > 0): ?>
                <?php while($review = mysqli_fetch_assoc($review_result)): ?>
                <div class="tips">
                    <div id="review-top">
                        <img src="../upload/patient/<?= $review['profile_img'] ?? '../img/user.jpg' ?>" alt="patient">
                        <h3><?= htmlspecialchars($review['patient_name']) ?></h3>
                    </div>
                    <p><?= htmlspecialchars($review['review_text']) ?></p>
                    <span id="rating">Rating: <?= number_format($review['rating'], 1) ?></span>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <p style="padding: 10px;">No Reviews Found</p>
                <?php endif; ?>
            </div>
                <!-- latest question -->
            <div class="box-forth">
                <div class="box-forth-top">
                    <h2>Latest Questions</h2>
                    <a href="doctor-qna.php">View All</a>
                </div>
                <?php
                $question_query = "SELECT q.*, p.name AS patient_name, p.profile_img
                   FROM question q
                   JOIN patient p ON q.patient_id = p.patient_id
                   ORDER BY q.question_time DESC
                   LIMIT 3";
                    $question_result = mysqli_query($conn, $question_query);
                    ?>
                <?php if (mysqli_num_rows($question_result) > 0): ?>
                <?php while($question = mysqli_fetch_assoc($question_result)): ?>
                <div class="qna">
                    <div id="qna-top">
                        <img src="../upload/patient/<?= $question['profile_img'] ?? '../img/user.jpg'?>" alt="patient">
                        <h3><?= htmlspecialchars($question['patient_name']) ?></h3>
                    </div>
                    <div id="question">
                        <h4><?= htmlspecialchars($question['question']) ?></h4>
                        <a id="ans" href="doctor-viewAnswer.php?question_id=<?= $question['question_id'] ?>" class="ans-btn">ANS</a>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <p style="padding: 10px;">No Questions Found</p>
                <?php endif; ?>
            </div>

            <!-- Latest Payments -->
            <div class="box-forth">
                <div class="box-forth-top">
                    <h2>Payments</h2>
                    <a href="doctor-earnings.php">View All</a>
                </div>
                <?php
                $payment_query = "SELECT pay.*, p.name AS patient_name, p.profile_img 
                  FROM transaction pay
                  JOIN patient p ON pay.patient_id = p.patient_id
                  WHERE pay.doctor_id = '$doctor_id'
                  ORDER BY pay.transaction_date DESC
                  LIMIT 3";
                    $payment_result = mysqli_query($conn, $payment_query);
                    ?>
                <?php if (mysqli_num_rows($payment_result) > 0): ?>
                <?php while($payment = mysqli_fetch_assoc($payment_result)): ?>
                <div class="box-forth-data payments">
                    <img src="../upload/patient/<?= $payment['profile_img'] ?? '../img/user.jpg'?>" alt="patient">
                    <div>
                        <h3><?= htmlspecialchars($payment['patient_name']) ?></h3>
                        <p><?= date('M d, Y h:i A', strtotime($payment['transaction_date'])) ?></p>
                    </div>
                    <data>₹<?= number_format($payment['amount'], 2) ?></data>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                <p style="padding: 10px;">No Payments Found</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <?php include 'doctor-footer.php'; ?>
</body>

</html>