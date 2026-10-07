<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Emergency Booking</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
    }

    .main-container {
      margin-left: 260px; 
      margin-top: 60px;  
      padding: 30px;
      max-width: calc(100% - 300px); 
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    h2 {
      color: #c62828;
      margin-bottom: 25px;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
      margin-bottom: 5px;
      color: #2e4450;
    }

    input, select, textarea {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }

    button {
      margin-top: 40px;
      padding: 12px 24px;
      background-color: #c62828;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      float: right;
    }

    button:hover {
      background-color: #a31515;
    }

    .form-note {
      font-size: 14px;
      color: #999;
    }

    .status-active {
      background-color: #ffd4d4;
      color: #c62828;
      padding: 6px 10px;
      border-radius: 20px;
      font-size: 14px;
      display: inline-block;
    }
  </style>
</head>
<body>

  <?php include 'patient-header.php' ?>

  <div class="main-container">
    <h2>⚠ Emergency Booking</h2>
    <form id="emergencyForm">
      <label for="patientName">Patient Name</label>
      <input type="text" id="patientName" placeholder="Enter patient's full name" required>

      <label for="phone">Mobile Number</label>
      <input type="tel" id="phone" placeholder="+91 XXXXX XXXXX" required>

      <label for="emergencyType">Emergency Type</label>
      <select id="emergencyType" required>
        <option value="">-- Select Emergency Type --</option>
        <option>Accident</option>
        <option>Cardiac Arrest</option>
        <option>Severe Burn</option>
        <option>Trauma</option>
        <option>Stroke</option>
      </select>

      <label for="location">Location <span class="form-note">(Optional)</span></label>
      <input type="text" id="location" placeholder="Provide location if possible">

      <label for="fileUpload">Upload Photo/Report <span class="form-note">(Optional)</span></label>
      <input type="file" id="fileUpload" accept="image/*,application/pdf">

      <button type="submit">🚨 Book Emergency</button>
    </form>
  </div>

  <script>
    document.getElementById('emergencyForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const patientName = document.getElementById('patientName').value.trim();
      const phone = document.getElementById('phone').value.trim();
      const emergencyType = document.getElementById('emergencyType').value;
      const location = document.getElementById('location').value;

      if (!patientName || !phone || !emergencyType) {
        alert("Please fill in all required fields.");
        return;
      }

      // Simulate form submit
      alert(`Emergency Booking Created!\n\nPatient: ${patientName}\nEmergency: ${emergencyType}\nPhone: ${phone}`);

      this.reset();
    });
  </script>

</body>
</html>
