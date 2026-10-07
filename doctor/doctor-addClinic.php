<?php
session_start();
include '../connection.php';

// Check if doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    header('Location: login.html');
    exit();
}

$doctor_id = $_SESSION['doctor_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Clinic</title>
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
    <?php include 'doctor-header.php'?>
    <main>
        <div class="container">
            <h2>Add Clinic Details <span style="font-size: 14px;">(Enable Browser Location for better result)</span>
            </h2>
            <form action="doctor-addClinic-submit.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <div>
                        <label>Clinic Name</label>
                        <input type="text" name="clinic_name" required>
                    </div>
                    <div>
                        <label>Clinic Image</label>
                        <input type="file" name="clinic_img">
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>Clinic Phone</label>
                        <input type="text" name="phone" required>
                    </div>
                    <div>
                        <label>Clinic Email</label>
                        <input type="email" name="email" required>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>Area</label>
                        <input type="text" name="area" required>
                    </div>
                    <div>
                        <label>City</label>
                        <input type="text" name="city" required>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>State</label>
                        <select name="state" required>
                            <option value="">Select</option>
                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                            <option value="Assam">Assam</option>
                            <option value="Bihar">Bihar</option>
                            <option value="Chhattisgarh">Chhattisgarh</option>
                            <option value="Goa">Goa</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Haryana">Haryana</option>
                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                            <option value="Jharkhand">Jharkhand</option>
                            <option value="Karnataka">Karnataka</option>
                            <option value="Kerala">Kerala</option>
                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Manipur">Manipur</option>
                            <option value="Meghalaya">Meghalaya</option>
                            <option value="Mizoram">Mizoram</option>
                            <option value="Nagaland">Nagaland</option>
                            <option value="Odisha">Odisha</option>
                            <option value="Punjab">Punjab</option>
                            <option value="Rajasthan">Rajasthan</option>
                            <option value="Sikkim">Sikkim</option>
                            <option value="Tamil Nadu">Tamil Nadu</option>
                            <option value="Telangana">Telangana</option>
                            <option value="Tripura">Tripura</option>
                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                            <option value="Uttarakhand">Uttarakhand</option>
                            <option value="West Bengal">West Bengal</option>
                        </select>
                    </div>

                    <div>
                        <label>Pincode</label>
                        <input type="text" name="pincode" required>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>Latitude</label>
                        <input type="text" name="latitude" id="latitude" readonly>
                    </div>
                    <div>
                        <label>Longitude</label>
                        <input type="text" name="longitude" id="longitude" readonly>
                    </div>
                </div>

                <div class="form-group full">
                    <div>
                        <label>Google Maps Location Link</label>
                        <input type="text" name="location_link">
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>Available Days</label>
                        <input type="text" name="days_available" placeholder="e.g., Mon, Tue, Wed">
                    </div>
                    <div>
                        <label>Status</label>
                        <select name="status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div>
                        <label>Opening Time</label>
                        <input type="time" name="opening_time" required>
                    </div>
                    <div>
                        <label>Closing Time</label>
                        <input type="time" name="closing_time" required>
                    </div>
                </div>

                <div class="form-group full">
                    <div>
                        <label>Consultation Fees</label>
                        <input type="number" name="fees" step="0.01" required>
                    </div>
                </div>

                <div class="submit-btn">
                    <button type="submit">Add Clinic</button>
                </div>
            </form>
        </div>
    </main>

    <script>
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
    </script>
    <?php include 'doctor-footer.php'?>
</body>

</html>