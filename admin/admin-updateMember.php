<?php
session_start();
include '../connection.php';
$team_id = $_GET['id']; // Get the ID from the URL


$sql = "SELECT * FROM our_team WHERE team_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $team_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("No record found for this ID.");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Team Member</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        margin: 40px auto;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: url('https://www.chieftalentofficer.co/wp-content/uploads/2022/04/AdobeStock_420089054-scaled.jpeg') no-repeat center center/cover;
    }

    .form-container {
        background: rgba(255, 255, 255, 0.95);
        padding: 40px 30px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        max-width: 600px;
        width: 100%;
    }

    h2 {
        text-align: center;
        color: #1e3a8a;
        margin-bottom: 25px;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 16px;
    }

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #1f2937;
        font-size: 14px;
    }

    input,
    textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 14px;
        transition: 0.3s;
    }

    input:focus,
    textarea:focus {
        border-color: #3b82f6;
        outline: none;
    }

    textarea {
        resize: vertical;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .form-grid .form-group {
        margin-bottom: 0;
    }

    button {
        width: 100%;
        padding: 12px;
        background-color: #2563eb;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 20px;
        transition: background 0.3s;
    }

    button:hover {
        background-color: #1d4ed8;
    }

    @media (max-width: 600px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Edit Team Member</h2>
        <form action="admin-updateMember-submit.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="team_id" value="<?php echo $team_id; ?>" />

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>"
                    required />
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>"
                    required />
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>"
                    required />
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <input type="text" id="role" name="role" value="<?php echo htmlspecialchars($row['role']); ?>"
                    required />
            </div>
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" rows="3"><?php echo htmlspecialchars($row['bio']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="profile_img">Profile Image</label>
                <input type="file" id="profile_img" name="profile_img" accept="image/*,.jpg,.jpeg,.gif,.bmp,.webp,.svg" />
                <p>Current Image: <?php echo htmlspecialchars($row['profile_img']); ?></p>
            </div>

            <!-- Social Links -->
            <div class="form-grid">
                <div class="form-group">
                    <label for="facebook">Facebook</label>
                    <input type="url" id="facebook" name="facebook"
                        value="<?php echo htmlspecialchars($row['facebook']); ?>" />
                </div>
                <div class="form-group">
                    <label for="instagram">Instagram</label>
                    <input type="url" id="instagram" name="instagram"
                        value="<?php echo htmlspecialchars($row['instagram']); ?>" />
                </div>
                <div class="form-group">
                    <label for="linkedin">LinkedIn</label>
                    <input type="url" id="linkedin" name="linkedin"
                        value="<?php echo htmlspecialchars($row['linkedin']); ?>" />
                </div>
                <div class="form-group">
                    <label for="github">GitHub</label>
                    <input type="url" id="github" name="github"
                        value="<?php echo htmlspecialchars($row['github']); ?>" />
                </div>
                <div class="form-group full-width">
                    <label for="portfolio">Portfolio</label>
                    <input type="url" id="portfolio" name="portfolio"
                        value="<?php echo htmlspecialchars($row['portfolio']); ?>" />
                </div>
            </div>

            <button type="submit">Update Member</button>
        </form>
    </div>
</body>

</html>