<?php
include '../connection.php'; // DB connection

$clinic_id = $_GET['clinic_id'] ?? 0;

$sql = "SELECT * FROM doctor_clinic WHERE clinic_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $clinic_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<p style='text-align:center;'>Clinic not found</p>";
    exit;
}

$clinic = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Clinic Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background: linear-gradient(135deg, #EFF6FF 0%, #FAF5FF 50%, #FDF2F8 100%);
        margin: 10px;
    }

    .clinic-view {
        max-width: 900px;
        margin: 40px auto;
        padding: 20px;
        box-shadow: 0 0 10px #ccc;
        border-radius: 10px;
        background: white;
    }

    .clinic-img-top {
        text-align: center;
        margin-bottom: 20px;
    }

    .clinic-img-top img {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 10px;
    }

    .clinic-view h2 {
        text-align: center;
        margin-bottom: 10px;
        font-size: 28px;
        color: #333;
    }

    .clinic-id {
        text-align: center;
        margin-bottom: 30px;
        font-size: 16px;
        color: #777;
    }

    .clinic-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .clinic-details div {
        margin-bottom: 12px;
    }

    .map-container {
        margin-top: 30px;
        width: 100%;
        height: 300px;
        border-radius: 10px;
        overflow: hidden;
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }

    .back-btn {
        display: block;
        margin: 30px auto 0;
        text-align: center;
    }

    .back-btn a {
        padding: 10px 20px;
        background: #0059b3;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }
    </style>
</head>

<body>

    <div class="clinic-view">
        <!-- Top Image -->
        <?php if (!empty($clinic['clinic_img']) && file_exists("../upload/doctor/" . $clinic['clinic_img'])): ?>
        <div class="clinic-img-top">
            <img src="../upload/doctor/<?= $clinic['clinic_img'] ?>" alt="Clinic Image">
        </div>
        <?php endif; ?>

        <!-- Clinic Name & ID -->
        <h2><?= htmlspecialchars($clinic['clinic_name']) ?></h2>
        <div class="clinic-id">(Clinic ID: <?= $clinic['clinic_id'] ?>)</div>

        <!-- Details -->
        <div class="clinic-details">
            <div><strong>Doctor ID:</strong> <?= $clinic['doctor_id'] ?></div>
            <div><strong>Area:</strong> <?= htmlspecialchars($clinic['area']) ?></div>
            <div><strong>City:</strong> <?= htmlspecialchars($clinic['city']) ?></div>
            <div><strong>State:</strong> <?= htmlspecialchars($clinic['state']) ?></div>
            <div><strong>Pincode:</strong> <?= htmlspecialchars($clinic['pincode']) ?></div>
            <div><strong>Days Available:</strong> <?= $clinic['days_available'] ?: 'NA' ?></div>
            <div><strong>Time Slot:</strong>
                <?= date("g:i A", strtotime($clinic['opening_time'])) ?> -
                <?= date("g:i A", strtotime($clinic['closing_time'])) ?>
            </div>
            <div><strong>Fees:</strong> ₹<?= number_format($clinic['fees'], 2) ?></div>
            <div><strong>Phone:</strong> <?= htmlspecialchars($clinic['phone']) ?></div>
            <div><strong>Email:</strong> <?= htmlspecialchars($clinic['email']) ?></div>
            <div><strong>Status:</strong> <?= ucfirst($clinic['status']) ?></div>
            <div><strong>Created At:</strong> <?= date("d M, Y, h:i A", strtotime($clinic['created_at'])) ?></div>
            <div><strong>Updated At:</strong> <?= date("d M, Y, h:i A", strtotime($clinic['updated_at'])) ?></div>
            <div><strong>Latitude:</strong> <?= $clinic['latitude'] ?></div>
            <div><strong>Longitude:</strong> <?= $clinic['longitude'] ?></div>
        </div>
        <!-- Back Button -->
        <!-- <div class="back-btn">
            <a href="doctor-clinic.php"><i class="fas fa-arrow-left"></i> Back to Clinic List</a>
        </div> -->
    </div>

    <?php if (!empty($clinic['location_link'])): ?>
    <div class="map-container">
        <iframe src="<?= htmlspecialchars($clinic['location_link']) ?>" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <?php else: ?>
    <div class="map-container"
        style="display: flex; align-items: center; justify-content: center; font-weight: bold; color: #555;">
        No location found
    </div>
    <?php endif; ?>

</body>

</html>