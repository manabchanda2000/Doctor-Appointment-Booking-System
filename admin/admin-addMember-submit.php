<?php
session_start();
include '../connection.php';

// Handling form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $role = htmlspecialchars($_POST['role']);
    $bio = htmlspecialchars($_POST['bio']);
    $facebook = htmlspecialchars($_POST['facebook']);
    $instagram = htmlspecialchars($_POST['instagram']);
    $linkedin = htmlspecialchars($_POST['linkedin']);
    $github = htmlspecialchars($_POST['github']);
    $portfolio = htmlspecialchars($_POST['portfolio']);

    // File upload handling
    $profile_img = null;
    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
        $img_tmp  = $_FILES['profile_img']['tmp_name'];
        $img_ext  = pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION);
        $profile_img = 'member_' . time() . '.' . $img_ext;
        move_uploaded_file($img_tmp, '../upload/admin/' . $profile_img);
    }


    // SQL query to insert data into the "our_team" table
    $sql = "INSERT INTO our_team (name, email, phone, role, bio, profile_img, facebook, instagram, linkedin, github, portfolio)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssss", $name, $email, $phone, $role, $bio, $profile_img, $facebook, $instagram, $linkedin, $github, $portfolio);

    if ($stmt->execute()) {
        echo "<script>
            alert('Team member added successfully!');
            window.location.href = 'admin-manageTeam.php'; // Optional: Redirect after success
        </script>";
    } else {
        echo "<script>
            alert('Error: " . $stmt->error . "');
            window.history.back(); // Optional: Go back to the form
        </script>";
    }
    

    $stmt->close();
}

$conn->close();
?>