<?php
// doctor-details.php
include '../connection.php'; // DB connection
$doc_id = $_GET['did'];
$clinic_id = $_GET['cid'];

// Doctor info
$doc_q = mysqli_query($conn, "SELECT * FROM doctor WHERE doctor_id='$doc_id'");
$doctor = mysqli_fetch_assoc($doc_q);

// Clinic info
$clinic_q = mysqli_query($conn, "SELECT * FROM doctor_clinic WHERE clinic_id='$clinic_id'");
$clinic = mysqli_fetch_assoc($clinic_q);

// Doctor reviews
$review_q = mysqli_query($conn, "
  SELECT r.*, p.name AS patient_name, p.profile_img AS patient_img 
  FROM review r 
  LEFT JOIN patient p ON r.patient_id = p.patient_id 
  WHERE r.review_for = 'doctor' AND r.doctor_id = '$doc_id'
  ORDER BY r.review_date DESC
");

// Calculate Age
$current_date = new DateTime();
$dob = new DateTime($doctor['dob']); // Assuming 'dob' column is in YYYY-MM-DD format
$age_interval = $current_date->diff($dob);
$age = $age_interval->y; // This gives the age in years

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Doctor & Clinic Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f8f9fa;
        padding: 40px;
    }

    .profile-card {
        max-width: 1100px;
        margin: auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: flex;
        flex-wrap: wrap;
    }

    .left-section {
        flex: 1;
        background: #e9f3ff;
        padding: 30px;
        text-align: center;
    }

    .left-section img {
        width: 180px;
        height: 180px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #007bff;
        margin-bottom: 20px;
    }

    .left-section h2 {
        margin: 10px 0 5px;
        color: #007bff;
    }

    .badge {
        display: inline-block;
        background: #28a745;
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        margin: 5px 0;
    }

    .right-section {
        flex: 2;
        padding: 30px;
    }

    .section-title {
        font-size: 20px;
        margin-bottom: 15px;
        color: #343a40;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 5px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px 30px;
        margin-bottom: 30px;
    }

    .info-grid p {
        margin: 0;
    }

    .info-grid p strong {
        color: #555;
    }

    .bio {
        grid-column: 1 / -1;
        /* This will make the bio take full width in the grid layout */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* reviews-------------------- */
    .reviews-section {
        max-width: 1100px;
        margin: 40px auto;
    }

    .review-title {
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 20px;
        border-bottom: 2px solid #ddd;
        padding-bottom: 8px;
        color: #333;
    }

    .review-box {
        background: #fff;
        padding: 20px;
        border-radius: 14px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
        margin-bottom: 20px;
        display: flex;
        gap: 20px;
    }

    .review-box img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #007bff;
    }

    .review-content {
        flex: 1;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .review-header h4 {
        margin: 0;
        font-size: 16px;
        color: #007bff;
    }

    .review-rating {
        color: #f0a500;
        font-size: 15px;
    }

    .review-text {
        margin: 8px 0;
        font-size: 15px;
        color: #444;
    }

    .review-footer {
        font-size: 12px;
        color: #666;
        float: right;
        
    }

    .back-button {
        display: inline-block;
        margin: 20px 0;
        padding: 8px 16px;
        background-color: #007BFF;
        color: #fff;
        text-decoration: none;
        font-weight: bold;
        border-radius: 6px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s;
    }

    .back-button:hover {
        background-color: #0056b3;
    }
    </style>
</head>

<body>

    <div class="profile-card">
        <!-- Left Section -->
        <div class="left-section">
            <img src="../upload/doctor/<?= $doctor['profile_img'] ?>" alt="<?= $doctor['name'] ?>">
            <h2><?= $doctor['name'] ?></h2>
            <div class="badge"><?= ucfirst($doctor['availability']) ?></div>
            <p>UID: <strong><?= $doctor['UID'] ?></strong></p>
            <p>Rating: <strong><?= $doctor['rating'] ?></strong> (<?= $doctor['total_reviews'] ?> reviews)</p>
            <a href="javascript:history.back()" class="back-button">← Back</a>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <!-- Doctor Details -->
            <div class="section-title">Doctor Details</div>
            <div class="info-grid">
                <p><strong>Email:</strong> <?= $doctor['email'] ?></p>
                <p><strong>Phone:</strong> <?= $doctor['phone'] ?></p>
                <p><strong>Specialization:</strong> <?= $doctor['specialization'] ?></p>
                <p><strong>Experience:</strong> <?= $doctor['experience'] ?> years</p>
                <p><strong>Qualification:</strong> <?= $doctor['qualification'] ?></p>
                <p><strong>Gender:</strong> <?= ucfirst($doctor['gender']) ?></p>
                <p><strong>Emergency Available:</strong> <?= ucfirst($doctor['emergency']) ?></p>
                <p><strong>Age:</strong> <?= $age ?> years</p>
                <p class="bio"><strong>Bio:</strong> <?= $doctor['bio'] ?: 'No bio provided.' ?></p>
            </div>

            <!-- Clinic Details -->
            <div class="section-title">Clinic Details</div>
            <div class="info-grid">
                <p><strong>Clinic Name:</strong> <?= $clinic['clinic_name'] ?></p>
                <p><strong>Area:</strong> <?= $clinic['area'] ?></p>
                <p><strong>City:</strong> <?= $clinic['city'] ?></p>
                <p><strong>State:</strong> <?= $clinic['state'] ?></p>
                <p><strong>Pincode:</strong> <?= $clinic['pincode'] ?></p>
                <p><strong>Days Available:</strong> <?= $clinic['days_available'] ?></p>
                <p><strong>Opening Time:</strong> <?= date("g:i A", strtotime($clinic['opening_time'])) ?></p>
                <p><strong>Closing Time:</strong> <?= date("g:i A", strtotime($clinic['closing_time'])) ?></p>
                <p><strong>Fees:</strong> ₹<?= $clinic['fees'] ?></p>
            </div>
        </div>
    </div>

    <div class="reviews-section">
        <div class="review-title">Reviews and Ratings</div>

        <?php
  if (mysqli_num_rows($review_q) > 0) {
    while ($rev = mysqli_fetch_assoc($review_q)) { ?>
        <div class="review-box">
            <img src="../upload/patient/<?= $rev['patient_img'] ?: 'default-user.png' ?>" alt="<?= $rev['patient_name'] ?>">
            <div class="review-content">
                <div class="review-header">
                    <h4><?= $rev['patient_name'] ?></h4>
                    <div class="review-rating">
                        <?= str_repeat('⭐', $rev['rating']) ?> (<?= $rev['rating'] ?>/5)
                    </div>
                </div>
                <div class="review-text">
                    <?= nl2br(htmlspecialchars($rev['review_text'])) ?>
                </div>
                <div class="review-footer">
                    <span><?= date("d M, Y, g:i A", strtotime($rev['review_date'])) ?></span>
                </div>
            </div>
        </div>
        <?php }
  } else {
    echo '<div style="background:#fefefe; padding: 20px; border-radius: 12px; text-align:center; color:#777; font-size:16px;">No reviews available for this doctor yet.</div>';
  }
  ?>
    </div>

</body>

</html>