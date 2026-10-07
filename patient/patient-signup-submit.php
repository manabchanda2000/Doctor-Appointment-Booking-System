<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/major-p/PHPMailer-master/src/Exception.php';
require 'C:/xampp/htdocs/major-p/PHPMailer-master/src/PHPMailer.php';
require 'C:/xampp/htdocs/major-p/PHPMailer-master/src/SMTP.php';

// Database Connection
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $checkQuery = "SELECT * FROM patient WHERE email = '$email'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('This email is already registered! Please login OR try another email.'); window.location.href = 'patient-login.html';</script>";
        exit();
    }

    // Password Hashing for Security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert Query
    $query = "INSERT INTO patient (name, email, password) VALUES ('$full_name', '$email', '$hashed_password')";
    if (mysqli_query($conn, $query)) {
        // Send Confirmation Email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Gmail SMTP Server
            $mail->SMTPAuth = true;
            $mail->Username = 'codergoals054@gmail.com'; // Gmail Email
            $mail->Password = 'okvb xeyx fmkx mrcg'; // Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Email Content
            $mail->setFrom('codergoals054@gmail.com', 'Apna Health');
            $mail->addAddress($email, $full_name);
            $mail->isHTML(true);
            $mail->Subject = 'Welcome to Apna Health';
            $mail->Body = "Hello $full_name, <br><br>Your account has been successfully created!<br><br>Best Regards,<br>Your Website Team";

            $mail->send();
            echo "<script>alert('Register successfully! A confirmation email has been sent.'); window.location.href = 'patient-login.html';</script>";
        } catch (Exception $e) {
            echo "Signup successful, but email could not be sent. Error: {$mail->ErrorInfo}";
        }
    } else {;
        echo "Error: " . mysqli_error($conn);
    }
}
?>
