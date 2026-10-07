<?php
session_start();
include '../connection.php';

// Handling form data submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $team_id = $_POST['team_id'];
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

    $query = "SELECT profile_img FROM our_team WHERE team_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $stmt->bind_result($existing_img);
    $stmt->fetch();
    $stmt->close();

    $profile_img = $existing_img;
    // File upload handling
        if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../upload/admin/';
            $img_tmp = $_FILES['profile_img']['tmp_name'];
            $img_ext = pathinfo($_FILES['profile_img']['name'], PATHINFO_EXTENSION);
            $profile_img = 'member_' . time() . '.' . $img_ext;
            if (!move_uploaded_file($img_tmp, $upload_dir . $profile_img)) {
                $_SESSION['error'] = "Error uploading the profile image.";
                header("Location: edit-team.php?id=$team_id");
                exit;
            }
        }


    // SQL query to update the data
    $sql = "UPDATE our_team 
            SET name = ?, email = ?, phone = ?, role = ?, bio = ?, profile_img = ?, facebook = ?, instagram = ?, linkedin = ?, github = ?, portfolio = ? 
            WHERE team_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssssi",
        $name,
        $email,
        $phone,
        $role,
        $bio,
        $profile_img,
        $facebook,
        $instagram,
        $linkedin,
        $github,
        $portfolio,
        $team_id
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "Team member updated successfully!";
        header("Location: admin-manageTeam.php"); // Redirect to the team list page
        exit;
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
        header("Location: edit-team.php?id=$team_id");
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['error'] = "Invalid request!";
    header("Location: team-list.php");
    exit;
}
?>