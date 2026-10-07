<?php
include '../connection.php';

// Get tip ID and fetch data
if (isset($_GET['tip_id'])) {
    $tips_id = mysqli_real_escape_string($conn, $_GET['tip_id']);

    $sql = "SELECT t.*, d.name AS doctor_name, d.specialization, d.profile_img AS doc_image 
            FROM health_tips t 
            JOIN doctor d ON t.doctor_id = d.doctor_id 
            WHERE t.tip_id = '$tips_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        echo "No details found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($row['title']) ?> - Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #e3f2fd;
            margin: 0;
            padding: 40px 20px;
            font-family: 'Segoe UI', sans-serif;
        }

        .details-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            max-width: 850px;
            margin: 0 auto;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .details-container h1 {
            color: #0d47a1;
            font-size: 32px;
            margin-bottom: 15px;
        }

        .details-container p {
            font-size: 16px;
            color: #424242;
            line-height: 1.6;
            margin-bottom: 10px;
            text-align: justify;
        }

        .tip-img {
            width: 100%;
            border-radius: 10px;
            margin-top: 20px;
        }

        .doctor-info {
            display: flex;
            align-items: center;
            margin-top: 30px;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 10px;
        }

        .doctor-info img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid #2196f3;
        }

        .doctor-info h3 {
            margin: 0;
            color: #0d47a1;
            font-size: 18px;
        }

        .doctor-info p {
            margin: 3px 0 0 0;
            font-size: 14px;
            color: #616161;
        }
    </style>
</head>

<body>
    <div class="details-container">
        <h1><?= htmlspecialchars($row['title']) ?></h1>
        <p><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></p>
        <p><strong>Description:</strong></p>
        <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>

        <?php if (!empty($row['image'])): ?>
            <img src="../upload/doctor/<?= htmlspecialchars($row['image']) ?>" alt="Health Tip Image" class="tip-img">
        <?php endif; ?>

        <p><strong>Created At:</strong> <?= date("F j, Y, h:i A", strtotime($row['created_at'])) ?></p>

        <!-- Doctor Info -->
        <div class="doctor-info">
            <img src="../upload/doctor/<?= htmlspecialchars($row['doc_image']) ?>" alt="Doctor">
            <div>
                <h3><?= htmlspecialchars($row['doctor_name']) ?></h3>
                <p><?= htmlspecialchars($row['specialization']) ?></p>
            </div>
        </div>
    </div>
</body>

</html>
