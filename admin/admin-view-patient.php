<?php
include '../connection.php';

// Retrieve patient ID from URL parameters
$patient_id = $_GET['id'] ?? null;

if (!$patient_id) {
    die("Patient ID is required to view the profile.");
}

// Fetch patient details from the database
$query = "SELECT * FROM patient WHERE patient_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No patient found with the provided ID.");
}

$patient = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Profile</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
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
            <img src="../upload/patient/<?= htmlspecialchars($patient['profile_img'] ?? 'default-user.jpg') ?>"
                alt="Patient Photo">
            <h1><?= htmlspecialchars($patient['name']) ?></h1>
        </div>
        <div class="profile-details">
            <div><strong>Email:</strong>
                <p><?= htmlspecialchars($patient['email']) ?></p>
            </div>
            <div><strong>Phone:</strong>
                <p><?= htmlspecialchars($patient['phone']) ?></p>
            </div>
            <div><strong>Gender:</strong>
                <p><?= htmlspecialchars(ucfirst($patient['gender'])) ?></p>
            </div>
            <div><strong>Date of Birth:</strong>
                <p><?= htmlspecialchars(date('d M, Y', strtotime($patient['dob']))) ?></p>
            </div>
            <div><strong>Blood Group:</strong>
                <p><?= htmlspecialchars($patient['blood_group'] ?? 'N/A') ?></p>
            </div>
            <div><strong>Area:</strong>
                <p><?= htmlspecialchars($patient['area'] ?? 'N/A') ?></p>
            </div>
            <div><strong>City:</strong>
                <p><?= htmlspecialchars($patient['city'] ?? 'N/A') ?></p>
            </div>
            <div><strong>State:</strong>
                <p><?= htmlspecialchars($patient['state'] ?? 'N/A') ?></p>
            </div>
            <div><strong>Pincode:</strong>
                <p><?= htmlspecialchars($patient['pincode'] ?? 'N/A') ?></p>
            </div>
            <div><strong>Emergency Contact:</strong>
                <p><?= htmlspecialchars($patient['emergency_contact'] ?? 'N/A') ?></p>
            </div>
            <div><strong>Availability:</strong>
                <p><?= htmlspecialchars(ucfirst($patient['availability'])) ?></p>
            </div>
        </div>
    </div>
</body>

</html>