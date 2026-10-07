<?php
session_start();
include '../connection.php';

// Check login
if (!isset($_SESSION['doctor_id'])) {
    header('Location: login.html');
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

// Check clinic ID
if (!isset($_GET['clinic_id'])) {
    echo "No clinic ID provided.";
    exit();
}

$clinic_id = $_GET['clinic_id'];
$query = "SELECT * FROM doctor_clinic WHERE clinic_id = ? AND doctor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $clinic_id, $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$clinic = $result->fetch_assoc();

if (!$clinic) {
    echo "Clinic not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Clinic</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
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

    .container h2 {
        margin-bottom: 20px;
        font-size: 26px;
        color: #111827;
    }

    .form-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group.full {
        grid-template-columns: 1fr;
    }

    label {
        font-weight: 600;
        margin-bottom: 6px;
        display: block;
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #BFDBFE;
        outline-color: #3B82F6;
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

    */
    </style>
</head>

<body>
    <?php include 'doctor-header.php' ?>
    <main>
        <div class="container">
            <h2>Edit Clinic Details</h2>
            <form action="clinic-edit-submit.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="clinic_id" value="<?= $clinic['clinic_id'] ?>">

                <div class="form-group">
                    <div>
                        <label>Clinic Name</label>
                        <input type="text" name="clinic_name" value="<?= htmlspecialchars($clinic['clinic_name']) ?>"
                            required>
                    </div>
                    <div>
                        <label>Clinic Image</label>
                        <input type="file" name="clinic_img">
                        <?php if (!empty($clinic['clinic_img'])): ?>
                        <p>Current: <strong><?= htmlspecialchars($clinic['clinic_img']) ?></strong></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>Clinic Phone</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($clinic['phone']) ?>" required>
                    </div>
                    <div>
                        <label>Clinic Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($clinic['email']) ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>Area</label>
                        <input type="text" name="area" value="<?= htmlspecialchars($clinic['area']) ?>" required>
                    </div>
                    <div>
                        <label>City</label>
                        <input type="text" name="city" value="<?= htmlspecialchars($clinic['city']) ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>State</label>
                        <select name="state" required>
                            <option value="">Select</option>
                            <?php
                        $states = ['Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal'];
                        foreach ($states as $state) {
                            $selected = ($clinic['state'] === $state) ? 'selected' : '';
                            echo "<option value=\"$state\" $selected>$state</option>";
                        }
                        ?>
                        </select>
                    </div>


                    <div>
                        <label>Pincode</label>
                        <input type="text" name="pincode" value="<?= htmlspecialchars($clinic['pincode']) ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>Latitude</label>
                        <input type="text" name="latitude" id="latitude"
                            value="<?= htmlspecialchars($clinic['latitude']) ?>" readonly>
                    </div>
                    <div>
                        <label>Longitude</label>
                        <input type="text" name="longitude" id="longitude"
                            value="<?= htmlspecialchars($clinic['longitude']) ?>" readonly>
                    </div>
                </div>

                <div class="form-group full">
                    <div>
                        <label>Google Maps Location Link</label>
                        <input type="text" name="location_link"
                            value="<?= htmlspecialchars($clinic['location_link']) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>Available Days</label>
                        <input type="text" name="days_available"
                            value="<?= htmlspecialchars($clinic['days_available']) ?>">
                    </div>
                    <div>
                        <label>Status</label>
                        <select name="status">
                            <option value="Active" <?= ($clinic['status'] === 'Active')   ? 'selected' : '' ?>>Active
                            </option>
                            <option value="Inactive" <?= ($clinic['status'] === 'Inactive') ? 'selected' : '' ?>>
                                Inactive</option>
                        </select>

                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>Opening Time</label>
                        <input type="time" name="opening_time" value="<?= $clinic['opening_time'] ?>" required>
                    </div>
                    <div>
                        <label>Closing Time</label>
                        <input type="time" name="closing_time" value="<?= $clinic['closing_time'] ?>" required>
                    </div>
                </div>

                <div class="form-group full">
                    <div>
                        <label>Consultation Fees</label>
                        <input type="number" name="fees" value="<?= $clinic['fees'] ?>" step="0.01" required>
                    </div>
                </div>

                <div class="submit-btn">
                    <button type="submit">Update Clinic</button>
                </div>
            </form>
        </div>
    </main>

    <!-- <script>
        window.onload = function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById("latitude").value = position.coords.latitude;
                    document.getElementById("longitude").value = position.coords.longitude;
                }, function(err) {
                    console.log("Location denied");
                });
            }
        };
    </script> -->
    <?php include 'doctor-footer.php' ?>
</body>

</html>