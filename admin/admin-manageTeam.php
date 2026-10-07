<?php
include '../connection.php';
session_start();

// Verify user authentication
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.html");
    exit();
}

$admin_id = $_SESSION['admin_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Team Members</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    main {
        background: linear-gradient(135deg, #f2f8fc, #f0f8ff);
        padding: 80px 20px 20px 280px;
    }

    /* first section----------------------- */
    .first-sec {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        padding: 10px;
        gap: 20px;
    }

    .heading-part {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    #add-btn {
        text-decoration: none;
        background-color: #2563EB;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        color: white;
        cursor: pointer;
        font-size: 14px;
    }

    /* second section------------------- */
    .second-sec {
        margin: 20px 0;
    }

    .members {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .team-card {
        flex: 0 0 400px;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: 2px 4px 6px rgba(0, 0, 0, 0.13);
        border-radius: 10px;
        background-color: #fdfdfd;
        padding: 15px;
        border: 1px solid #BFDBFE;
    }

    .team-card img {
        height: 100px;
        width: 100px;
        border-radius: 50%;
        border: 2px solid #3B82F6;
        margin-bottom: 5px;
    }

    .team-card h3 {
        font-size: 18px;
    }

    .team-card p {
        color: #4B5563;
        font-size: 14px;
        text-align: center;
    }

    .contact {
        display: flex;
        align-items: center;
        gap: 5px;
        margin: 5px 0;
    }

    .socials {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 5px;
        width: 100%;
        margin: 10px 0;
    }

    .socials a {
        text-decoration: none;
        color: #2563EB;
        font-size: 18px;
    }

    .action-btn {
        display: flex;
        align-items: center;
        width: 100%;
        gap: 10px;
        justify-content: space-between;
    }

    .action-btn a {
        text-decoration: none;
        color: white;
        padding: 6px 20px;
        border-radius: 5px;
        border: 1px solid transparent;
        transition: all .3s ease;
    }

    #edit {
        background-color: #f59e0b;
    }

    #edit:hover {
        background-color: transparent;
        color: black;
        border: 1px solid #f59e0b;
    }

    #delete {
        background-color: #dc2626;
    }

    #delete:hover {
        background-color: transparent;
        color: black;
        border: 1px solid #dc2626;
    }
    </style>
</head>

<body>
    <?php include 'admin-header.php'?>
    <main>
        <section class="first-sec">
            <div class="heading-part">
                <h2>Manage Team Members</h2>
                <a href="admin-addMember.php" id="add-btn">Add Members</a>
            </div>
        </section>

        <section class="second-sec">
            <div class="members">
                <?php
                $query = "SELECT * FROM our_team ORDER BY created_at DESC";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='team-card'>
                            <img src='../upload/admin/{$row['profile_img']}' alt='{$row['name']}' class='profile-img'>
                            <h3 class='name'>{$row['name']}</h3>
                            <p class='role'>{$row['role']}</p>
                            <p class='contact'>
                                <i class='fa-solid fa-envelope'></i>{$row['email']} <br>
                                <i class='fa-solid fa-phone'></i>{$row['phone']}
                            </p>
                            <p class='bio'>{$row['bio']}</p>
                            <div class='socials'>
                                <a href='{$row['facebook']}' title='Facebook' target='_blank'><i class='fa-brands fa-facebook'></i></a>
                                <a href='{$row['instagram']}' title='Instagram' target='_blank'><i class='fa-brands fa-instagram'></i></a>
                                <a href='{$row['linkedin']}' title='LinkedIn' target='_blank'><i class='fa-brands fa-linkedin'></i></a>
                                <a href='{$row['github']}' title='GitHub' target='_blank'><i class='fa-brands fa-github'></i></a>
                                <a href='{$row['portfolio']}' title='Portfolio' target='_blank'><i class='fa-solid fa-globe'></i></a>
                            </div>
                            <div class='action-btn'>
                                <a href='admin-updateMember.php?id={$row['team_id']}' id='edit'><i class='fa-solid fa-pen-to-square' title='Edit Info'></i> Edit</a>
                                <a href='admin-deleteMember.php?id={$row['team_id']}' id='delete'><i class='fa-solid fa-trash' title='Delete User'></i> Delete</a>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<p>No team members found.</p>";
                }
                ?>

            </div>
        </section>
    </main>
    <?php include 'admin-footer.php'?>
</body>

</html>