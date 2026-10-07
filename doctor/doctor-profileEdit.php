<?php
session_start();
include '../connection.php';

// Check if doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    header('Location: login.html');
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

// Fetch doctor data
$stmt = $conn->prepare("SELECT * FROM doctor WHERE doctor_id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

// Default image if none
$default_img = "../img/user.jpg";
$img = !empty($doctor['profile_img']) ? '../upload/doctor/' . $doctor['profile_img'] : $default_img;
function show($value) {
    return htmlspecialchars($value ?? 'N/A');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Doctor Profile</title>
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

    .doctor-id {
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
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #BFDBFE;
        outline-color: #3B82F6;
        border-radius: 6px;
    }

    .gender,
    .emergency {
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
    <?php include 'doctor-header.php'?>
    <main>
        <div class="container">
            <form action="doctor-profileEdit-submit.php" method="POST" enctype="multipart/form-data" class="form">
                <div class="profile-header">
                    <h2>Profile Settings</h2>
                    <div class="img">
                        <img src="<?= $img ?>" alt="doctor Profile">
                        <label for="imgUpload"><i class="fa fa-camera"></i></label>
                        <input type="file" id="imgUpload" name="profile_img" style="display: none">
                    </div>
                    <div class="doctor-id">Doctor ID: <?= show($doctor['doctor_id']) ?><br>UID:
                        <?= show($doctor['UID']) ?></div>
                </div>
                <h3>Personal Information</h3>
                <div class="form-group">
                    <div>
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?= show($doctor['name']) ?>" required>
                    </div>
                    <div>
                        <label>Email Address</label>
                        <input type="email" name="email" value="<?= show($doctor['email']) ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?= show($doctor['phone']) ?>" required>
                    </div>
                    <div>
                        <label>Date of Birth</label>
                        <input type="date" name="dob" value="<?= $doctor['dob'] ?? '' ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>Gender</label>
                        <div class="gender">
                            <label><input type="radio" name="gender" value="male"
                                    <?= ($doctor['gender'] === 'Male') ? 'checked' : '' ?>> Male</label>
                            <label><input type="radio" name="gender" value="female"
                                    <?= ($doctor['gender'] === 'Female') ? 'checked' : '' ?>> Female</label>
                            <label><input type="radio" name="gender" value="Other"
                                    <?= ($doctor['gender'] === 'Other') ? 'checked' : '' ?>> Other</label>

                        </div>
                    </div>
                    <div>
                        <label>Address</label>
                        <input type="text" name="address" value="<?= $doctor['address'] ?? '' ?>" required>
                    </div>
                </div>

                <h3>Proffessional Information</h3>
                <div class="form-group">
                    <div>
                        <label>Specialization</label>
                        <select name="specialization">
                            <option value="">Select</option>
                            <?php
                            $specializations = ['Cardiology', 'Neurology', 'Orthopedics', 'Pediatrics', 'Dermatology', 'Psychiatry', 'General Surgery', 'Radiology'];
                            foreach ($specializations as $spec) {
                                $selected = ($doctor['specialization'] === $spec) ? 'selected' : '';
                                echo "<option value=\"$spec\" $selected>$spec</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label>Eperience(mention only year)</label>
                        <input type="text" name="experience" value="<?= show($doctor['experience']) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>Qualification</label>
                        <input type="text" name="qualification" value="<?= show($doctor['qualification']) ?>">
                    </div>
                    <div>
                        <label>Consultation Fees</label>
                        <input type="number" name="fees" value="<?= show($doctor['fees']) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>Emergency</label>
                        <div class="emergency">
                            <label>
                                <input type="radio" name="emergency" value="yes"
                                    <?= ($doctor['emergency'] === 'Yes') ? 'checked' : '' ?>> Yes
                            </label>
                            <label>
                                <input type="radio" name="emergency" value="no"
                                    <?= ($doctor['emergency'] === 'No') ? 'checked' : '' ?>> No
                            </label>
                        </div>
                    </div>
                </div>

                <h3>About Yourself</h3>
                <div class="form-group full">
                    <div>
                        <textarea name="bio"><?= show($doctor['bio']) ?></textarea>
                    </div>
                </div>

                <div class="submit-btn">
                    <button type="submit">Update Profile</button>
                </div>
            </form>
        </div>
    </main>
    <?php include 'doctor-footer.php'?>
</body>

</html>