<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Team Member</title>
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
        <h2>Add Team Member</h2>
        <form action="admin-addMember-submit.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required />
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required />
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" required />
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <input type="text" id="role" name="role" required />
            </div>
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="profile_img">Profile Image</label>
                <input type="file" id="profile_img" name="profile_img" />
            </div>

            <!-- Social Links -->
            <div class="form-grid">
                <div class="form-group">
                    <label for="facebook">Facebook</label>
                    <input type="url" id="facebook" name="facebook" />
                </div>
                <div class="form-group">
                    <label for="instagram">Instagram</label>
                    <input type="url" id="instagram" name="instagram" />
                </div>
                <div class="form-group">
                    <label for="linkedin">LinkedIn</label>
                    <input type="url" id="linkedin" name="linkedin" />
                </div>
                <div class="form-group">
                    <label for="github">GitHub</label>
                    <input type="url" id="github" name="github" />
                </div>
                <div class="form-group full-width">
                    <label for="portfolio">Portfolio</label>
                    <input type="url" id="portfolio" name="portfolio" />
                </div>
            </div>

            <button type="submit">Add Member</button>
        </form>
    </div>
</body>

</html>