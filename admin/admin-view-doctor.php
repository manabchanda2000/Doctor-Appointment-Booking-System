<?php
include '../connection.php';

// Retrieve doctor ID from URL parameters
$doctor_id = $_GET['id'] ?? null;

if (!$doctor_id) {
    die("Doctor ID is required to view the profile.");
}

// Fetch doctor details from the database
$query = "SELECT * FROM doctor WHERE doctor_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No doctor found with the provided ID.");
}

$doctor = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Profile</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
    /* Styling for the doctor profile card */
    .profile-container {
        max-width: 900px;
        margin: 50px auto;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        background-color: #f9f9f9;
    }

    .profile-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .profile-header img {
        border-radius: 50%;
        width: 150px;
        height: 150px;
        object-fit: cover;
    }

    .profile-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    .profile-details div {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .profile-bio {
        grid-column: span 2;
        background: #eef6fc;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .profile-bio h3 {
        margin-bottom: 10px;
        color: #2563eb;
    }

    .highlight {
        color: #2563eb;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <div class="profile-container">
        <div class="profile-header">
            <img src="../upload/doctor/<?= htmlspecialchars($doctor['profile_img'] ?? 'default-user.jpg') ?>"
                alt="Doctor Photo">
            <h1><?= htmlspecialchars($doctor['name']) ?></h1>
            <h3><?= htmlspecialchars($doctor['specialization']) ?></h3>
        </div>
        <div class="profile-details">
            <div>
                <strong>Doctor ID/UID:</strong>
                <p><?= htmlspecialchars($doctor['doctor_id']) ?> / <?= htmlspecialchars($doctor['UID']) ?></p>
            </div>
            <div>
                <strong>Email:</strong>
                <p><?= htmlspecialchars($doctor['email']) ?></p>
            </div>
            <div>
                <strong>Phone:</strong>
                <p><?= htmlspecialchars($doctor['phone']) ?></p>
            </div>
            <div>
                <strong>Gender:</strong>
                <p><?= htmlspecialchars(ucfirst($doctor['gender'])) ?></p>
            </div>
            <div>
                <strong>Date of Birth:</strong>
                <p><?= htmlspecialchars(date('d M, Y', strtotime($doctor['dob']))) ?></p>
            </div>
            <div>
                <strong>Experience:</strong>
                <p><?= htmlspecialchars($doctor['experience']) ?> years</p>
            </div>
            <div>
                <strong>Qualification:</strong>
                <p><?= htmlspecialchars($doctor['qualification']) ?></p>
            </div>
            <div>
                <strong>Address:</strong>
                <p><?= htmlspecialchars($doctor['address']) ?></p>
            </div>
            <div>
                <strong>Fees:</strong>
                <p><i class="fa-solid fa-indian-rupee-sign"></i> <?= htmlspecialchars($doctor['fees']) ?></p>
            </div>
            <div>
                <strong>Emergency Service:</strong>
                <p><?= htmlspecialchars(ucfirst($doctor['emergency'])) ?></p>
            </div>
            <div>
                <strong>Availability:</strong>
                <p><?= htmlspecialchars(ucfirst($doctor['availability'])) ?></p>
            </div>
            <div>
                <strong>Rating:</strong>
                <p><?= htmlspecialchars($doctor['rating']) ?> (<?= htmlspecialchars($doctor['total_reviews']) ?>
                    reviews)</p>
            </div>
        </div>
        <div class="profile-bio">
            <h3>Bio:</h3>
            <p><?= nl2br(htmlspecialchars($doctor['bio'] ?? 'Not provided')) ?></p>
        </div>
    </div>
</body>

</html>