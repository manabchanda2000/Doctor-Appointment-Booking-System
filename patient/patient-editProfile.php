<?php
session_start();
include '../connection.php';

// Check if patient is logged in
if (!isset($_SESSION['patient_id'])) {
    header('Location: login.html');
    exit();
}

$patient_id = $_SESSION['patient_id'];

// Fetch patient data
$stmt = $conn->prepare("SELECT * FROM patient WHERE patient_id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

// Default image if none
$default_img = "../img/user.jpg";
$img = !empty($patient['profile_img']) ? '../upload/patient/' . $patient['profile_img'] : $default_img;
function show($value) {
    return htmlspecialchars($value ?? 'N/A');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Patient Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    main {
        background: linear-gradient(135deg, #EFF6FF 0%, #FAF5FF 50%, #FDF2F8 100%);
        padding: 100px 0 20px;

    }

    .container {
        max-width: 900px;
        background: #fdfdfd;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin: 0 auto;
    }

    .profile-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    .container h2 {
        font-size: 24px;
    }

    .img img {
        height: 80px;
        width: 80px;
        border-radius: 50%;
        object-fit: fill;
        border: 3px solid #ddd;
    }

    .patient-id {
        font-size: 14px;
        color: #3B82F6;
        background: #E5F0FF;
        padding: 6px 12px;
        border-radius: 10px;
    }

    .profile-header label {
        position: relative;
        right: 30px;
        background: #3B82F6;
        padding: 6px;
        border-radius: 50%;
        cursor: pointer;
        color: white;
    }

    .profile-header label i {
        font-size: 14px;
    }

    .form h3 {
        font-size: 20px;
        margin: 20px 0;
        padding: 10px 0;
        border-top: 2px solid #dae9ff;
    }

    .form-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 25px;
    }

    .form-group.full {
        grid-template-columns: 1fr;
    }

    .form-group label {
        font-weight: 500;
        margin-bottom: 5px;
        display: block;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #BFDBFE;
        outline-color: #3B82F6;
        border-radius: 6px;
    }

    .gender {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .gender input {
        margin-right: 6px;
    }

    .submit-btn {
        text-align: center;
        margin-top: 30px;
    }

    .submit-btn button {
        background: #3B82F6;
        color: white;
        border: none;
        padding: 12px 28px;
        font-size: 16px;
        border-radius: 6px;
        cursor: pointer;
    }
    </style>
</head>

<body>
    <?php include 'patient-header.php'?>
    <main>
        <div class="container">
            <form action="patient-editProfile-submit.php" method="POST" enctype="multipart/form-data" class="form">
                <div class="profile-header">
                    <h2>Profile Settings</h2>
                    <div class="img">
                        <img src="<?= $img ?>" alt="Patient Profile">
                        <label for="imgUpload"><i class="fa fa-camera"></i></label>
                        <input type="file" id="imgUpload" name="profile_img" style="display: none">
                    </div>
                    <div class="patient-id">Patient ID: <?= show($patient['patient_id']) ?></div>
                </div>
                <h3>Personal Information</h3>
                <div class="form-group">
                    <div>
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?= show($patient['name']) ?>" required>
                    </div>
                    <div>
                        <label>Email Address</label>
                        <input type="email" name="email" value="<?= show($patient['email']) ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?= show($patient['phone']) ?>" required>
                    </div>
                    <div>
                        <label>Date of Birth</label>
                        <input type="date" name="dob" value="<?= $patient['dob'] ?? '' ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>Gender</label>
                        <div class="gender">
                            <label><input type="radio" name="gender" value="Male"
                                    <?= ($patient['gender'] === 'Male') ? 'checked' : '' ?>> Male</label>
                            <label><input type="radio" name="gender" value="Female"
                                    <?= ($patient['gender'] === 'Female') ? 'checked' : '' ?>> Female</label>
                            <label><input type="radio" name="gender" value="Other"
                                    <?= ($patient['gender'] === 'Other') ? 'checked' : '' ?>> Other</label>

                        </div>
                    </div>
                    <div>
                        <label>Blood Group</label>
                        <select name="blood_group">
                            <option value="">Select</option>
                            <?php
                            $blood_groups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                            foreach ($blood_groups as $bg) {
                                $selected = ($patient['blood_group'] === $bg) ? 'selected' : '';
                                echo "<option value=\"$bg\" $selected>$bg</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div>
                        <label>Emergency Conatct</label>
                        <input type="text" name="emergency_contact" value="<?= show($patient['emergency_contact']) ?>">
                    </div>
                </div>

                <h3>Address Information</h3>
                <div class="form-group full">
                    <label>Street Address</label>
                    <input type="text" name="area" value="<?= show($patient['area']) ?>">
                </div>

                <div class="form-group">
                    <div>
                        <label>City</label>
                        <input type="text" name="city" value="<?= show($patient['city']) ?>">
                    </div>
                    <div>
                        <label>State</label>
                        <input type="text" name="state" value="<?= show($patient['state']) ?>">
                    </div>
                </div>

                <div class="form-group full">
                    <label>Pin Code</label>
                    <input type="text" name="pincode" value="<?= show($patient['pincode']) ?>">
                </div>

                <div class="submit-btn">
                    <button type="submit">Update Profile</button>
                </div>
            </form>
        </div>
    </main>
    <?php include 'patient-footer.php'?>
</body>

</html>